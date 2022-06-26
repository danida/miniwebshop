package dev.mails.consumer;

import org.json.JSONObject;
import org.springframework.amqp.rabbit.annotation.RabbitListener;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import dev.mails.template.OrderCompletedTemplate;

@Component
public class SendOrderCompletedNotificationConsumer {

    @Autowired
    private OrderCompletedTemplate emailTemplate;

    @RabbitListener(queues = "emails.send-order-completed-notification")
    public void listener(String input) {
        System.out.println("emails.send-order-completed-notification");
        System.out.println(input);

        System.out.println("Sending email...");

        JSONObject  jsonObject = new JSONObject(input);
        emailTemplate.send("mike@example.com", jsonObject.getString("orderId"));

        System.out.println("Email sent");
    }
}
