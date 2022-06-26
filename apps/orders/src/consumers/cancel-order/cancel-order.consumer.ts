import { AmqpConnection, RabbitSubscribe } from '@golevelup/nestjs-rabbitmq';
import { Injectable, Logger } from '@nestjs/common';
import { Payload } from '@nestjs/microservices';
import { OrderState } from 'src/enums';
import { OrderService } from 'src/services/order';
import { CancelOrderInput } from './cancel-order.input';

@Injectable()
export class CancelOrderConsumer {
  private logger: Logger = new Logger();

  constructor(
    private ordersService: OrderService,
    private readonly amqpConnection: AmqpConnection,
  ) {}

  @RabbitSubscribe({
    exchange: 'payments',
    routingKey: 'payment.failed',
    queue: 'orders.cancel-order',
    queueOptions: {
      exclusive: false,
      durable: false,
      autoDelete: true,
    },
  })
  handlerPaymentFailed(@Payload() data: CancelOrderInput) {
    return this.handler(data);
  }

  @RabbitSubscribe({
    exchange: 'shippings',
    routingKey: 'shipping.failed',
    queue: 'orders.cancel-order',
    queueOptions: {
      exclusive: false,
      durable: false,
      autoDelete: true,
    },
  })
  handlerShippingFailed(@Payload() data: CancelOrderInput) {
    return this.handler(data);
  }

  private async handler(data: CancelOrderInput) {
    this.logger.log("EventHandler: 'orders.cancel-order'", { data });

    await this.ordersService.updateOrder(data.orderId, {
      state: OrderState.CANCELLED,
    });

    this.amqpConnection.publish('orders', 'order.cancelled', {
      orderId: data.orderId,
    });
  }
}
