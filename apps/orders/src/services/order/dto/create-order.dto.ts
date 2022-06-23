import { Order, OrderProduct } from '@prisma/client';

export type CreateOrderDTO = Omit<Order, 'id' | 'timestamp'> & {
  products: Omit<OrderProduct, 'id'>[];
};

export type UpdateOrderDTO = Partial<Omit<Order, 'id' | 'timestamp'>>;
