from rabbitmq.consumer import register_consumer
from rabbitmq.consumers import create_shipping_consumer
from rabbitmq import start_consuming


def main():
    register_consumer("shippings.create-shipping", create_shipping_consumer)
    start_consuming()
    pass

if __name__ == "__main__":
    main()
