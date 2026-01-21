<?php

class bankAccount
{
    public function __construct(
        private float $balance = 0
    ) {
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Invalid amount");
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Invalid amount");
        }
        if ($this->balance < $amount) {
            throw new InvalidArgumentException("Not enough dineros");
        }
        $this->balance -= $amount;
    }

}

$brokenAccount = new bankAccount();
echo $brokenAccount->getBalance();
echo '<br>';
$brokenAccount->deposit(100);
echo $brokenAccount->getBalance();
echo '<br>';
try {
    $brokenAccount->withdraw(200);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
}
echo '<br>';
echo $brokenAccount->getBalance();
echo '<br>';
$brokenAccount->withdraw(10);
echo $brokenAccount->getBalance();
echo '<br>';
