import json
import pika
from typing import Callable

exchanges = [
    {
        "name": "payments",
    },
    {
        "name": "shippings",
    },
]

queues = [
    {
        "name": "shippings.create-shipping",
        "bind_exchange": {
            "name": "payments",
            "routing_key": "payment.success",
        },
    },
]

rmq_consumers = dict()


# Registering consumers
def register_consumer(queue: str, callback: Callable):
    rmq_consumers[queue] = callback


# Creating connection
connection = pika.BlockingConnection(pika.ConnectionParameters(host='rabbitmq'))
channel = connection.channel()
channel.basic_qos(prefetch_count=1)


# Declaring exchanges and queues
for exchange in exchanges:
    channel.exchange_declare(exchange=exchange["name"], exchange_type='topic', durable=False, auto_delete=True)

for queue in queues:
    channel.queue_declare(queue=queue["name"], exclusive=False, durable=False, auto_delete=True)
    channel.queue_bind(exchange=queue["bind_exchange"]["name"], routing_key=queue["bind_exchange"]["routing_key"], queue=queue["name"])


# Starting consuming
def start_consuming():
    print('Waiting for events')

    def makeCallback(queue: str):
        def callback(ch, method, properties, body):
            def ack():
                channel.basic_ack(method.delivery_tag)

            if queue in rmq_consumers:
                rmq_consumers[queue](
                    body=json.loads(body),
                    ack=ack,
                    channel=ch
                )
            else:
                print("no consumer found")
                ack()
        return callback

    for queue in queues:
        channel.basic_consume(queue=queue["name"], on_message_callback=makeCallback(queue["name"]), auto_ack=False)

    channel.start_consuming()
