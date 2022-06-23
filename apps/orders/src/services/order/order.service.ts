import { Injectable } from '@nestjs/common';
import { Order } from '@prisma/client';
import { PrismaService } from '../prisma';
import { CreateOrderDTO, UpdateOrderDTO } from './dto/create-order.dto';

@Injectable()
export class OrderService {
  constructor(private prisma: PrismaService) {}

  async createOrder(data: CreateOrderDTO): Promise<Order> {
    const newOrder: Order = await this.prisma.order.create({
      data: {
        timestamp: new Date(),
        userId: data.userId,
        cartId: data.cartId,
        state: data.state,
        products: {
          create: data.products.map((_product) => ({
            productId: _product.productId,
            quantity: _product.quantity,
            price: _product.price,
          })),
        },
      },
    });

    const order: Order = await this.prisma.order.findUnique({
      where: {
        id: newOrder.id,
      },
      include: {
        products: true,
      },
    });

    return order;
  }

  async updateOrder(id: string, data: UpdateOrderDTO): Promise<void> {
    await this.prisma.order.update({
      where: {
        id: id,
      },
      data: data,
    });

    return;
  }
}
