<?php

namespace Bifrost\Class;

use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Class\Processor;
use Bifrost\DataTypes\DateTime;
use Bifrost\Model\Payment as ModelPayment;

/**
 * @property UUID $id
 * @property Money $amount
 * @property Processor|null $processedBy
 * @property DateTime|null $processedAt
 */
class Payment implements \JsonSerializable
{

    public UUID $id;
    private array $data = [];

    public function __construct(UUID $id)
    {
        $this->id = $id;
    }

    public function __get(string $name)
    {
        switch ($name) {
            case 'id':
                return $this->id;
            default:
                if (empty($this->data[$name])) {
                    $this->data = ModelPayment::get($this->id);
                }

                return $this->data[$name] ?? null;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'amount' => $this->__get('amount')->getValue(),
            'processed_by' => $this->__get('processedBy'),
            'processed_at' => $this->__get('processedAt'),
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
