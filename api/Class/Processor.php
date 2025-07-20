<?php

namespace Bifrost\Class;

use Bifrost\DataTypes\Tax;
use Bifrost\DataTypes\Url;
use Bifrost\DataTypes\UUID;
use Bifrost\Model\Processor as ModelProcessor;

/**
 * @property UUID $id
 * @property string $name
 * @property Tax $tax
 * @property Url $url
 */
class Processor implements \JsonSerializable
{
    public UUID $id;
    private array $data = [];

    public function __construct(UUID $id)
    {
        $this->id = $id;
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'id':
                return $this->id;
            default:
                if (empty($this->data[$name])) {
                    $this->data = ModelProcessor::get($this->id);
                }

                return $this->data[$name] ?? null;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->data['name'] ?? null,
            'tax' => $this->data['tax'] ?? null,
            'url' => $this->data['url'] ?? null,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
