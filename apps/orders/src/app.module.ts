import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq';
import { Module } from '@nestjs/common';
import { CreateOrderConsumer, CompleteOrderConsumer } from './consumers';
import { CancelOrderConsumer } from './consumers/cancel-order';
import { OrderService, PrismaService } from './services';

@Module({
  imports: [
    RabbitMQModule.forRoot(RabbitMQModule, {
      exchanges: [
        {
          name: 'carts',
          type: 'topic',
          options: {
            durable: false,
            autoDelete: true,
          },
        },
        {
          name: 'orders',
          type: 'topic',
          options: {
            durable: false,
            autoDelete: true,
          },
        },
        {
          name: 'shippings',
          type: 'topic',
          options: {
            durable: false,
            autoDelete: true,
          },
        },
      ],
      prefetchCount: 1,
      uri: `amqp://${process.env.RABBITMQ_HOST}:${
        process.env.RABBITMQ_PORT || 5672
      }`,
    }),
  ],
  providers: [
    PrismaService,
    OrderService,
    ...[CreateOrderConsumer, CompleteOrderConsumer, CancelOrderConsumer],
  ],
})
export class AppModule {}
