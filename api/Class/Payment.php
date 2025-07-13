<?php

namespace Bifrost\Class;

use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Class\Processor;
use Bifrost\DataTypes\DateTime;
use Bifrost\Model\Payment as ModelPayment;

class Payment implements \JsonSerializable
{

    public UUID $id;
    public Money $amount;
    public Processor|null $processedBy = null;
    public DateTime|null $processedAt = null;

    public function __construct(UUID $id)
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'amount' => (float) $this->amount,
            'processed_by' => $this->processedBy,
            'processed_at' => $this->processedAt,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Cadastra um novo pagamento
     */
    public static function new(UUID $id, Money $amount): self
    {
        return ModelPayment::new($id, $amount);
    }
}
