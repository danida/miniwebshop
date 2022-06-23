import { AmqpConnection, RabbitSubscribe } from '@golevelup/nestjs-rabbitmq';
import { Injectable, Logger } from '@nestjs/common';
import { Payload } from '@nestjs/microservices';
import { OrderState } from 'src/enums';
import { OrderService } from 'src/services/order';
import { CompleteOrderInput } from './complete-order.input';

@Injectable()
export class CompleteOrderConsumer {
  private logger: Logger = new Logger();

  constructor(
    private ordersService: OrderService,
    private readonly amqpConnection: AmqpConnection,
  ) {}

  @RabbitSubscribe({
    exchange: 'shippings',
    routingKey: 'shipping.success',
    queue: 'orders.complete-order',
    queueOptions: {
      exclusive: false,
      durable: false,
      autoDelete: true,
    },
  })
  async handler(@Payload() data: CompleteOrderInput) {
    this.logger.log("EventHandler: 'orders.complete-order'", { data });

    await this.ordersService.updateOrder(data.orderId, {
      state: OrderState.COMPLETED,
    });

    this.amqpConnection.publish('orders', 'order.completed', {
      orderId: data.orderId,
    });
  }
}
