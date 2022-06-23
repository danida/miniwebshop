# RabbitMQ
## Exchanges
| name | type
| -- | --
| carts | topic
| orders | topic
| payments | topic
| shippings | topic
| emails | topic

## Queue bindings
| exchange | routing key | name
| -- | -- | --
| carts | cart.checkout | orders.create-order
| orders | order.created | payments.make-payment
| orders | order.completed | emails.send-notification
| orders | order.failed | emails.send-notification
| payments | payment.success | shippings.create-shipping
| payments | payment.failed | orders.cancel-order
| payments | payment.failed | emails.send-notification
| shippings | shipping.success | orders.complete-order
| shippings | shipping.failed | orders.cancel-order
| shippings | shipping.failed | payments.cancel-payment
| shippings | shipping.failed | emails.send-notification
