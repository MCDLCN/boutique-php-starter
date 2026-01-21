<?php

abstract class Payment
{
    public function __construct(
        protected float $amount
    ) {
    }

    // Méthode concrète : partagée par tous
    public function getAmount(): float
    {
        return $this->amount;
    }

    // Méthode abstraite : DOIT être implémentée par les enfants
    abstract public function process(): bool;
    abstract public function getProviderName(): string;
}

class CardPayment extends Payment
{
    public function process(): bool
    {
        return true;
    }

    public function getProviderName(): string
    {
        return 'Stripe';
    }
}

class PaypalPayment extends Payment
{
    public function process(): bool
    {
        return true;
    }

    public function getProviderName(): string
    {
        return 'Paypal';
    }
}

class BankPayment extends Payment
{
    public function process(): bool
    {
        return true;
    }

    public function getProviderName(): string
    {
        return 'Bank';
    }
}

// ❌ ERREUR : impossible d'instancier une classe abstraite
// $payment = new Payment(100);

// ✅ OK : instancier une classe concrète
$payment = new CardPayment(100);
echo $payment->getAmount();
// À toi : crée PaypalPayment et BankTransferPayment
