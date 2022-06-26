package dev.mails.config;

import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Bean;
import org.springframework.amqp.core.Binding;
import org.springframework.amqp.core.BindingBuilder;
import org.springframework.amqp.core.Queue;
import org.springframework.amqp.core.TopicExchange;

@Configuration
public class RabbitMQConfig {
    // Queues
    @Bean
    public Queue sendOrderCompletedNotificationQueue() {
        return new Queue("emails.send-order-completed-notification", false, false, true);
    }

    @Bean
    public Queue sendOrderCancelledNotificationQueue() {
        return new Queue("emails.send-order-cancelled-notification", false, false, true);
    }


    // Topics
    @Bean
    public TopicExchange shippingsExchange() {
        return new TopicExchange("orders", false, true);
    }

    
    // Bindings
    @Bean
    public Binding binding1() {
        return BindingBuilder.bind(sendOrderCompletedNotificationQueue()).to(shippingsExchange()).with("order.completed");
    }

    @Bean
    public Binding binding2() {
        return BindingBuilder.bind(sendOrderCancelledNotificationQueue()).to(shippingsExchange()).with("order.cancelled");
    }
}
