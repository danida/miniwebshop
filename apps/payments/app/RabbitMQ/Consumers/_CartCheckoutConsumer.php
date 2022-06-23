<?php

namespace App\RabbitMQ\Consumers;

use App\RabbitMQ\ConsumerContract;
use App\RabbitMQ\Consumer;
use App\RabbitMQ\Publishers\PaymentSuccessPublisher;
use App\Models\Payment;
use App\Services\PaymentService;
use PhpAmqpLib\Message\AMQPMessage;

class CartCheckoutConsumer extends Consumer implements ConsumerContract
{
    protected $queue = "cart.checkout";

    public function handle(AMQPMessage $message, $data)
    {
        print_r("cart.checkout\n");
        print_r($data);

        $message->ack();
    }
}
