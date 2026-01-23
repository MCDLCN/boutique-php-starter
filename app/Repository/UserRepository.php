<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use PDO;
use PDOException;
use RuntimeException;

final class UserRepository
{
    public function __construct(
        private PDO $pdo,
        private AddressRepository $addressRepo
    ) {
    }

    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, password_hash, registrationDate
             FROM users
             WHERE id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, password_hash, registrationDate
             FROM users
             WHERE email = ?'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findWithAddresses(?User $user): ?User
    {
        if (!$user instanceof User) {
            return null;
        }
        $id = $user->getId();
        if ($id === null) {
            throw new RuntimeException("This user doesn't exist");
        }
        $addresses = $this->addressRepo->findByUserId($id);
        foreach ($addresses as $address) {
            $user->addAddress($address);
        }

        return $user;
    }

    /** @return User[] */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, name, email, password_hash, registrationDate
             FROM users
             ORDER BY id DESC'
        );
        if ($stmt === false) {
            throw new RuntimeException('Query failed');
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'hydrate'], $rows);
    }

    // CREATE (hash password)
    // Throws RuntimeException if email already exists
    public function save(User $user, string $plainPassword): void
    {
        if ($plainPassword === '') {
            throw new RuntimeException('Password cannot be empty');
        }

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (name, email, password_hash, registrationDate)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                $user->getName(),
                $user->getEmail(),
                $hash,
                $user->getRegistrationDate(), // or date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new RuntimeException('Email already exists', $e->getCode(), $e);
            }
            throw $e;
        }

        $user->setId((int)$this->pdo->lastInsertId());
    }

    // UPDATE
    public function update(User $user): void
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE users SET name = ?, email = ? WHERE id = ?'
            );
            $stmt->execute([
                $user->getName(),
                $user->getEmail(),
                $user->getId()
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new RuntimeException('Email already exists', $e->getCode(), $e);
            }
            throw $e;
        }
    }

    // UPDATE (password)
    public function updatePassword(User $user, string $plainPassword): void
    {
        if ($plainPassword === '') {
            throw new RuntimeException('Password cannot be empty');
        }

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$hash, $user->getId()]);
    }

    // AUTH helper
    public function verifyCredentials(string $email, string $plainPassword): ?User
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, password_hash, registrationDate
             FROM users
             WHERE email = ?'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        if (!password_verify($plainPassword, (string)$row['password_hash'])) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function delete(User $user): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$user->getId()]);
    }

    public function existsEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * Summary of hydrate
     * @param array{
     * id: int|string,
     * name: string,
     * email: string,
     * registrationDate: string
     * } $row
     */
    private function hydrate(array $row): User
    {
        return new User(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['email'],
            (string)$row['registrationDate'],
            []
        );
    }
}
