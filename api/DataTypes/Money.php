<?php

namespace Bifrost\DataTypes;

use Bifrost\Enum\Field;
use Bifrost\Include\AbstractFieldValue;

class Money
{
    use AbstractFieldValue;

    public function __construct(mixed $value)
    {
        $this->init($value, Field::MONEY);
    }
}
