package dev.mails.template;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.stereotype.Component;

@Component
public class OrderCompletedTemplate {

    @Autowired
    private JavaMailSender emailSender;

    public void send(
            String to, String orderId) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setFrom("noreply@example.com");
        message.setTo(to);
        message.setSubject("Order Completed");
        message.setText(String.format("Hi! Your order '%s' is completed now!", orderId));
        emailSender.send(message);
    }
}
