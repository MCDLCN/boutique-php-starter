<?php
declare(strict_types=1);

final class AddressRepository
{
    public function __construct(private PDO $pdo) {}

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
    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, user_id, road, city, postal_code, country, is_default
             FROM addresses
             WHERE user_id = ?
             ORDER BY is_default DESC, id ASC"
        );
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'hydrate'], $rows);
    }

    // CREATE: returns new address id
    public function save(int $userId, Address $address, bool $isDefault = false): int
    {
        if ($isDefault) {
            $this->pdo->beginTransaction();
            try {
                $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
                $stmt->execute([$userId]);

                $stmt = $this->pdo->prepare(
                    "INSERT INTO addresses (user_id, road, city, postal_code, country, is_default)
                     VALUES (?, ?, ?, ?, ?, 1)"
                );
                $stmt->execute([
                    $userId,
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
            $userId,
            $address->getRoad(),
            $address->getCity(),
            (string)$address->getPostalCode(),
            $address->getCountry(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $addressId, Address $address): void
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
            $addressId,
        ]);
    }

    public function delete(int $addressId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM addresses WHERE id = ?");
        $stmt->execute([$addressId]);
    }

    public function setDefault(int $userId, int $addressId): void
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $this->pdo->prepare("UPDATE addresses SET is_default = 1 WHERE user_id = ? AND id = ?");
            $stmt->execute([$userId, $addressId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function hydrate(array $row): Address
    {
        // Your Address class doesn't include id/userId/isDefault, so we only hydrate the value fields.
        // If you later add those properties, update this method.
        return new Address(
            (string)$row['road'],
            (string)$row['city'],
            (int)$row['postal_code'],
            (string)$row['country']
        );
    }
}
