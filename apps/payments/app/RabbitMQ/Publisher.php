<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use Illuminate\Support\Facades\App;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher
{
    private AMQPChannel $channel;

    function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    public static function publish($data)
    {
        $publisherSingleton = App::make(get_called_class());
        // $publisherSingleton->handle($data);

        $channel = $publisherSingleton->getChannel();
        $exchange = $publisherSingleton->getExchange();
        $routingKey = $publisherSingleton->getRoutingKey();
        $msg = new AMQPMessage(json_encode($data));

        $channel->basic_publish($msg, $exchange, $routingKey);
    }
}
