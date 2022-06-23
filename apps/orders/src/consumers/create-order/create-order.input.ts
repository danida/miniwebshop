import { Order, OrderProduct } from '@prisma/client';

export type CreateOrderInput = {
  userId: string,
  cart: Omit<Order, 'id' | 'userId' | 'timestamp'> & {
    products: Omit<OrderProduct, 'id'>[];
  };
  cardDetails: {
    number: string,
    expiry: string,
    cvc: string,
  }
};
