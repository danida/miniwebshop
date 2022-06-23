import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq';
import { Module } from '@nestjs/common';
import { CreateOrderConsumer, CompleteOrderConsumer } from './consumers';
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
      uri: 'amqp://rabbitmq:5672',
    }),
  ],
  providers: [
    PrismaService,
    OrderService,
    ...[CreateOrderConsumer, CompleteOrderConsumer],
  ],
})
export class AppModule {}
