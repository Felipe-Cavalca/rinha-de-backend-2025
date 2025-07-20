<?php

namespace Bifrost\Controller;

use Bifrost\Attributes\Details;
use Bifrost\Attributes\Method;
use Bifrost\Attributes\RequiredFields;
use Bifrost\Class\HttpResponse;
use Bifrost\Class\Payment;
use Bifrost\Core\Database;
use Bifrost\Core\Post;
use Bifrost\Core\Queue;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Enum\Field;
use Bifrost\Interface\ControllerInterface;
use Bifrost\Tasks\savePayment;

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
        $queue = new Queue();
        $post = new Post();
        $id = new UUID(value: $post->correlationId);
        $amount = new Money(value: $post->amount);
        $savePaymentTask = new savePayment(id: $id, amount: $amount);
        $queue->addToEnd(task: $savePaymentTask);

        return HttpResponse::created(
            objName: "payment",
            data: [
                "id" => (string) $id,
                "amount" => $amount->getValue(),
            ]
        );
    }
}
