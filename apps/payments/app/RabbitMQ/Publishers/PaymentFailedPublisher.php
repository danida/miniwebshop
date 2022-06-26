<?php

namespace App\RabbitMQ\Publishers;

use \App\RabbitMQ\PublisherContract;
use \App\RabbitMQ\Publisher;

class PaymentFailedPublisher extends Publisher implements PublisherContract
{
    protected $exchange = "payments";
    protected $routingKey = "payment.failed";
}
