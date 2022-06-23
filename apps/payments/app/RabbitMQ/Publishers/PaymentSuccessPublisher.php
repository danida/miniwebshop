<?php

namespace App\RabbitMQ\Publishers;

use \App\RabbitMQ\PublisherContract;
use \App\RabbitMQ\Publisher;

class PaymentSuccessPublisher extends Publisher implements PublisherContract
{
    protected $exchange = "payments";
    protected $routingKey = "payment.success";
}
