<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Channel\AMQPChannel;

class RabbitMQConsumerService
{
    private array $connectionDetails;
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private string $exchange;
    private string $queue;
    private array $consumers;
    private array $publishers;
    private array $consumerContaner = [];

    private $exchanges;
    private $queues;

    // function __construct(
    //     array $connectionDetails,
    //     string $exchange,
    //     string $queue,
    //     array $consumers,
    //     array $publishers,
    // ) {
    //     $this->connectionDetails = $connectionDetails;
    //     $this->exchange = $exchange;
    //     $this->queue = $queue;
    //     $this->consumers = $consumers;
    //     $this->publishers = $publishers;

    //     $this->createConnection();
    //     $this->declareExchange();
    //     $this->declareQueue();
    //     $this->registerConsumers();
    //     $this->registerPublishers();
    // }

    function __construct(
        array $connectionDetails,
        array $exchanges,
        array $queues,
        array $consumers,
        array $publishers,
    ) {
        $this->connectionDetails = $connectionDetails;
        $this->exchanges = $exchanges;
        $this->queues = $queues;
        $this->consumers = $consumers;
        $this->publishers = $publishers;

        $this->createConnection();
        $this->declareExchanges();
        $this->declareQueues();
        $this->registerConsumers();
        $this->registerPublishers();
    }

    private function createConnection(): void
    {
        $connection = new AMQPStreamConnection(
            $this->connectionDetails["host"],
            $this->connectionDetails["port"],
            $this->connectionDetails["user"],
            $this->connectionDetails["password"]
        );

        $this->channel = $connection->channel();

        $this->channel->basic_qos(null, 1, null);

        $this->connection = $connection;
    }

    private function declareExchanges(): void
    {
        foreach ($this->exchanges as $exchange) {
            $this->channel->exchange_declare(
                $exchange["name"],
                $exchange["type"],
                false,
                $exchange["durable"],
                $exchange["auto_delete"]
            );
        }
    }

    private function declareQueues(): void
    {        
        foreach ($this->queues as $queue) {
            $this->channel->queue_declare($queue["name"], false, $queue["durable"], $queue["exclusive"], $queue["auto_delete"]);
            $this->channel->queue_bind($queue["name"], $queue["bind_exchange"]["name"], $queue["bind_exchange"]["routing_key"]);
        }
    }

    private function registerConsumers(): void
    {
        echo ("Registering consumers...\n");
        foreach ($this->consumers as $consumer) {
            App::when($consumer)
                ->needs(AMQPChannel::class)
                ->give(function () {
                    return $this->channel;
                });

            $consumerInstance = App::make($consumer);
            $queue = $consumerInstance->getQueue();

            $this->consumerContaner[$queue] = $consumer;

            echo ("Queue \"{$queue}\" registered for consumer \"{$consumer}\"\n");
        }
        echo ("\n");
    }

    private function registerPublishers(): void
    {
        echo ("Registering publishers...\n");
        foreach ($this->publishers as $publisher) {
            App::when($publisher)
                ->needs(AMQPChannel::class)
                ->give(function () {
                    return $this->channel;
                });

            echo ("Publisher \"{$publisher}\" registered\n");
        }
        echo ("\n");
    }

    public function consume(): void
    {
        echo ("Listening for events...\n");
        $makeCallback = function($queue) {
            return function ($msg) use ($queue) {
                echo ("Message received for queue \"{$queue}\"\n");

                if (array_key_exists($queue, $this->consumerContaner)) {
                    $consumerInstance = App::make($this->consumerContaner[$queue]);
                    $consumerInstance->handle($msg, json_decode($msg->body, true));
                    echo ("Message has been processed\n");
                } else {
                    $msg->nack();
                    echo ("No consumer found\n");
                }
            };
        };

        foreach ($this->queues as $queue) {
            $this->channel->basic_consume($queue["name"], '', false, false, false, false, $makeCallback($queue["name"]));
        }

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}
