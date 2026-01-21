<?php

declare(strict_types=1);

final class AddressRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function find(int $id): ?Address
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, user_id, road, city, postal_code, country, is_default
             FROM addresses
             WHERE id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /** @return Address[] */
    public function findByUserId(User $user): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, user_id, road, city, postal_code, country, is_default
             FROM addresses
             WHERE user_id = ?
             ORDER BY is_default DESC, id ASC"
        );
        $stmt->execute([$user->getId()]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'hydrate'], $rows);
    }

    // CREATE: returns new address id
    public function save(User $user, Address $address): int
    {
        if ($address->isDefault()) {
            $this->pdo->beginTransaction();
            try {
                $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
                $stmt->execute([$user->getId()]);

                $stmt = $this->pdo->prepare(
                    "INSERT INTO addresses (user_id, road, city, postal_code, country, is_default)
                     VALUES (?, ?, ?, ?, ?, 1)"
                );
                $stmt->execute([
                    $user->getId(),
                    $address->getRoad(),
                    $address->getCity(),
                    (string)$address->getPostalCode(),
                    $address->getCountry(),
                ]);

                $id = (int)$this->pdo->lastInsertId();
                $this->pdo->commit();
                return $id;
            } catch (Throwable $e) {
                $this->pdo->rollBack();
                throw $e;
            }
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO addresses (user_id, road, city, postal_code, country, is_default)
             VALUES (?, ?, ?, ?, ?, 0)"
        );
        $stmt->execute([
            $user->getId(),
            $address->getRoad(),
            $address->getCity(),
            (string)$address->getPostalCode(),
            $address->getCountry(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    //UPDATE
    public function update(Address $address): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE addresses
             SET road = ?, city = ?, postal_code = ?, country = ?
             WHERE id = ?"
        );
        $stmt->execute([
            $address->getRoad(),
            $address->getCity(),
            (string)$address->getPostalCode(),
            $address->getCountry(),
            $address->getId()
        ]);
    }

    //DELETE
    public function delete(Address $address): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM addresses WHERE id = ?");
        $stmt->execute([$address->getId()]);
    }

    //Set address as default
    public function setDefault(User $user, Address $address): void
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
            $stmt->execute([$user->getId()]);

            $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 1 WHERE user_id = ? AND id = ?");
            $stmt->execute([$user->getId(), $address->getId()]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function hydrate(array $row): Address
    {
        return new Address(
            (int) $row["id"],
            (int) $row["user_id"],
            (string)$row['road'],
            (string)$row['city'],
            (int)$row['postal_code'],
            (string)$row['country'],
            (bool) $row['is_default']
        );
    }
}
