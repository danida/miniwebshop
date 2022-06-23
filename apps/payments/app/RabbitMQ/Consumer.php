<?php

namespace App\RabbitMQ;

class Consumer
{
    public function getExchange(): string
    {
        return $this->exchange;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }
}
