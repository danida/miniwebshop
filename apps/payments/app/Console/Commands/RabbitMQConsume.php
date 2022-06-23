<?php

namespace App\Console\Commands;

use App\Services\RabbitMQConsumerService;
use Illuminate\Console\Command;

class RabbitMQConsume extends Command
{
    protected $signature = 'rabbitmq:consume';

    protected $description = 'Consume RabbitMQ';

    public function handle()
    {
        $consumers = [
            \App\RabbitMQ\Consumers\MakePaymentConsumer::class,
            // \App\RabbitMQ\Consumers\CartCheckoutConsumer::class,
        ];

        $publishers = [
            \App\RabbitMQ\Publishers\PaymentSuccessPublisher::class,
        ];

        $consumer = new RabbitMQConsumerService(
            [
                "host" => "rabbitmq",
                "port" => 5672,
                "user" => "guest",
                "password" => "guest",
            ],
            [
                [
                    "name" => "orders",
                    "type" => "topic",
                    "durable" => false,
                    "exclusive" => false,
                    "auto_delete" => true,
                ],
                [
                    "name" => "payments",
                    "type" => "topic",
                    "durable" => false,
                    "exclusive" => false,
                    "auto_delete" => true,
                ]
            ],
            [
                [
                    "name" => "payments.make-payment",
                    "durable" => false,
                    "exclusive" => false,
                    "auto_delete" => true,
                    "bind_exchange" => [
                        "name" => "orders",
                        "routing_key" => "order.created",
                    ]
                ]
            ],
            $consumers,
            $publishers,
        );

        $consumer->consume();

        return 0;
    }
}
