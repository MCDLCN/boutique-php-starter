<?php

class QueryBuilder
{
    private string $table;
    private array $conditions = [];
    private array $params = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private ?int $offset = null;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $this->conditions[] = [
            'boolean' => 'AND',
            'sql' => "$field $operator ?",
            'value' => $value
        ];
        return $this;
    }

    public function orWhere(string $field, string $operator, mixed $value): self
    {
        $this->conditions[] = [
            'boolean' => 'OR',
            'sql' => "$field $operator ?",
            'value' => $value
        ];
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'ASC';
        }

        $this->orderBy[] = $field . ' ' . $direction;
        return $this;
    }

    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= ' WHERE ';

            foreach ($this->conditions as $i => $cond) {
                if ($i > 0) {
                    $sql .= ' ' . $cond['boolean'] . ' ';
                }
                $sql .= $cond['sql'];
                $this->params[] = $cond['value'];
            }
        }

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;

            if ($this->offset !== null && $this->offset > 0) {
                $sql .= ' OFFSET ' . $this->offset;
            }
        }

        return $sql;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}

// Utilisation
// $qb = new QueryBuilder('produits');
// $qb->where('prix', '<=', 100)
//    ->where('stock', '>', 0)
//    ->orderBy('prix', 'ASC')
//    ->limit(10);

// $stmt = $pdo->prepare($qb->getSQL());
// $stmt->execute($qb->getParams());
