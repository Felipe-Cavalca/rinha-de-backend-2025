<?php

namespace Bifrost\Tasks;

use Bifrost\Class\Payment;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Interface\TaskInterface;

class savePayment implements TaskInterface
{
    private UUID $id;
    private Money $amount;

    public function __construct(UUID $id, Money $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    public function __serialize(): array
    {
        return [
            'id' => serialize($this->id),
            'amount' => serialize($this->amount),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = unserialize($data['id']);
        $this->amount = unserialize($data['amount']);
    }

    public function run(): bool
    {
        try {
            Payment::new(id: $this->id, amount: $this->amount);
        } catch (\Exception $e) {
            // Handle the exception, log it, or rethrow it as needed
            error_log("Failed to save payment: " . $e->getMessage());
        }
        return true;
    }
}
