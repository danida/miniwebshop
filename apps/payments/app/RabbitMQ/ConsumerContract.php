<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;

interface ConsumerContract
{
    public function handle(AMQPMessage $message, mixed $data);
}
