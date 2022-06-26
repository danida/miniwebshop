package dev.mails.consumer;

import org.json.JSONObject;
import org.springframework.amqp.rabbit.annotation.RabbitListener;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import dev.mails.template.OrderCancelledTemplate;

@Component
public class SendOrderCancelledNotificationConsumer {

    @Autowired
    private OrderCancelledTemplate emailTemplate;

    @RabbitListener(queues = "emails.send-order-cancelled-notification")
    public void listener(String input) {
        System.out.println("emails.send-order-cancelled-notification");
        System.out.println(input);

        System.out.println("Sending email...");

        JSONObject jsonObject = new JSONObject(input);
        emailTemplate.send("mike@example.com", jsonObject.getString("orderId"));

        System.out.println("Email sent");
    }
}
