<?php

class Order
{
    public function __construct(
        public int $id,
        public User $user,
        public Cart $items,
        public string $statut
    ) {
    }

    public function getTotal(): int
    {
        return $this->items->getTotal() ?? [];
    }

    public function getItemCount(): int
    {
        return $this->items->count();
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
