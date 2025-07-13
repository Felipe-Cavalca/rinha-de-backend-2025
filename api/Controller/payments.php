<?php

namespace Bifrost\Controller;

use Bifrost\Attributes\Details;
use Bifrost\Attributes\Method;
use Bifrost\Attributes\RequiredFields;
use Bifrost\Class\HttpResponse;
use Bifrost\Class\Payment;
use Bifrost\Core\Database;
use Bifrost\Core\Post;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Enum\Field;
use Bifrost\Interface\ControllerInterface;

class Payments implements ControllerInterface
{

    #[Method("POST")]
    #[RequiredFields([
        "correlationId" => Field::UUID,
        "amount" => Field::MONEY,
    ])]
    #[Details([
        "description" => "Create a new payment"
    ])]
    public function index(): HttpResponse
    {
        $post = new Post();
        $id = new UUID(value: $post->correlationId);
        $amount = new Money(value: $post->amount);
        $payment = Payment::new(id: $id, amount: $amount);

        return HttpResponse::created(
            objName: "payment",
            data: $payment->toArray()
        );
    }
}
