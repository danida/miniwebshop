import { Injectable } from '@nestjs/common';
import { Order, OrderProduct } from '@prisma/client';
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
        totalPrice: this.getTotalPrice(data.products),
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

  private getTotalPrice(products: Omit<OrderProduct, 'id'>[]): number {
    return products.reduce((accu, curr, idx) => {
      return accu + curr.price * curr.quantity;
    }, 0);
  }
}
