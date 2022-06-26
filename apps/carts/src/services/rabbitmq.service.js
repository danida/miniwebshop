const amqp = require("amqplib/channel_api");

let connection = null;
let channel = null;

async function connect() {
  connection = await amqp.connect(
    `amqp://${process.env.RABBITMQ_HOST}:${process.env.RABBITMQ_PORT}`
  );

  channel = await connection.createChannel();

  channel.assertExchange("carts", "topic", {
    durable: false,
    autoDelete: true,
  });

  console.log("connect success");

  return {
    connection,
    channel,
  };
}

function getChannel() {
  return channel;
}

connect();

module.exports = {
  connect,
  channel,
  getChannel,
};
