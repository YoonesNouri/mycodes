const { Kafka } = require('kafkajs');
const WebSocket = require('ws');

// set Kafka
const kafka = new Kafka({
  clientId: 'js-client',
  brokers: ['localhost:9092'],
});

const consumer = kafka.consumer({ groupId: 'websocket_group' });

// set WebSocket
const wss = new WebSocket.Server({ port: 8080 });

wss.on('connection', (ws) => {
  console.log('WebSocket client connected');
});

const run = async () => {
  await consumer.connect();
  await consumer.subscribe({ topic: 'coffee_orders', fromBeginning: true });

  console.log('Kafka consumer is listening...');

  await consumer.run({
    eachMessage: async ({ topic, partition, message }) => {
      const msg = message.value.toString();
      console.log(`Received message: ${msg}`);

      // Send message to all clients connected to WebSocket
      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send(msg);
        }
      });
    },
  });
};

run().catch(console.error);
