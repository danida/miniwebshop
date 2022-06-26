const rabbitmqService = require("../services/rabbitmq.service");

async function checkout(req, res, next) {
  const msg = JSON.stringify({
    userId: `userid-${Math.random()}`,
    cart: {
      cartId: `cartid-${Math.random()}`,
      products: [
        {
          productId: `productid-${Math.random()}`,
          price: 1271,
          quantity: 3,
        },
      ],
    },
    cardDetails: {
      number: 3232323232323231,
      cvc: 726,
      expiry: "44/12",
    },
  });
  rabbitmqService
    .getChannel()
    .publish("carts", "cart.checkout", Buffer.from(msg));

  res.json({
    success: true,
  });
}

module.exports = {
  checkout,
};
