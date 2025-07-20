<?php

namespace Bifrost\Tasks;

use Bifrost\Class\Payment;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Interface\TaskInterface;

class savePayment implements TaskInterface
{
    public function __construct(
        private UUID $id,
        private Money $amount,
    ) {}

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
        Payment::new(id: $this->id, amount: $this->amount);
        return true;
    }
}
