import { AmqpConnection, RabbitSubscribe } from '@golevelup/nestjs-rabbitmq';
import { Injectable, Logger } from '@nestjs/common';
import { Payload } from '@nestjs/microservices';
import { Order } from '@prisma/client';
import { OrderState } from 'src/enums';
import { OrderService } from 'src/services/order';
import { CreateOrderInput } from './create-order.input';

@Injectable()
export class CreateOrderConsumer {
  private logger: Logger = new Logger();

  constructor(
    private ordersService: OrderService,
    private readonly amqpConnection: AmqpConnection,
  ) {}

  @RabbitSubscribe({
    exchange: 'carts',
    routingKey: 'cart.checkout',
    queue: 'orders.create-order',
    queueOptions: {
      exclusive: false,
      durable: false,
      autoDelete: true,
    },
  })
  async handler(@Payload() data: CreateOrderInput) {
    this.logger.log("EventHandler: 'orders.create-order'", { data });

    const order: Order = await this.ordersService.createOrder({
      userId: data.userId,
      cartId: data.cart.cartId,
      products: data.cart.products,
      state: OrderState.PENDING,
    });

    this.amqpConnection.publish('orders', 'order.created', {
      orderId: order.id,
      totalPrice: 1200,
      cardDetails: data.cardDetails,
    });
  }
}
