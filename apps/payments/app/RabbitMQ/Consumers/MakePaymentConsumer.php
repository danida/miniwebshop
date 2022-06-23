<?php

namespace App\RabbitMQ\Consumers;

use App\RabbitMQ\ConsumerContract;
use App\RabbitMQ\Consumer;
use App\RabbitMQ\Publishers\PaymentSuccessPublisher;
use App\Models\Payment;
use App\Services\PaymentService;
use PhpAmqpLib\Message\AMQPMessage;

class MakePaymentConsumer extends Consumer implements ConsumerContract
{
    protected $queue = "payments.make-payment";

    private PaymentService $payment;

    function __construct(PaymentService $payment)
    {
        $this->payment = $payment;
    }

    public function handle(AMQPMessage $message, $data)
    {
        print("MakePaymentConsumer\n");
        print_r($data);

        $this->payment->payWithCard(
            $data["cardDetails"]["number"],
            $data["cardDetails"]["cvc"],
            $data["cardDetails"]["expiry"]
        );

        $payment = new Payment();
        $payment->orderId = $data["orderId"];
        $payment->totalPrice = $data["totalPrice"];
        $payment->save();

        PaymentSuccessPublisher::publish([
            "orderId" => $data["orderId"],
            "paymentId" => $payment->id,
        ]);

        $message->ack();
    }
}
