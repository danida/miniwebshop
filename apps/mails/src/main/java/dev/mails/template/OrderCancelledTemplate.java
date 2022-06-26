package dev.mails.template;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.stereotype.Component;

@Component
public class OrderCancelledTemplate {

    @Autowired
    private JavaMailSender emailSender;

    public void send(
            String to, String orderId) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setFrom("noreply@example.com");
        message.setTo(to);
        message.setSubject("Order Cancelled");
        message.setText(String.format("Hi! Your order '%s' has been cancelled.", orderId));
        emailSender.send(message);
    }
}
