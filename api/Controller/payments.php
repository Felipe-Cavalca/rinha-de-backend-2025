<?php

namespace Bifrost\Controller;

use Bifrost\Core\Post;
use Bifrost\Core\Queue;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;
use Bifrost\Interface\ControllerInterface;
use Bifrost\Tasks\savePayment;

class Payments implements ControllerInterface
{

    public function index()
    {
        $queue = new Queue();
        $post = new Post();
        $id = new UUID(value: $post->correlationId);
        $amount = new Money(value: $post->amount);
        $savePaymentTask = new savePayment(id: $id, amount: $amount);
        $queue->addToEnd(task: $savePaymentTask);
    }
}
