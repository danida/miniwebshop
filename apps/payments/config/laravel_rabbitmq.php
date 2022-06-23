<?php
/** For documentation, see https://github.com/needle-project/laravel-rabbitmq */
return [
    'connections' => [
        'main_connection' => [
            'hostname' => 'localhost',
            'lazy' => false
        ]
    ],
    'exchanges' => [
        'logs_topic' => [
            'connection' => 'main_connection',
            'name' => 'logs_topic',
            'attributes' => [
                'exchange_type' => 'topic',
                'durable' => true,
            ]
        ]
    ],
    'queues' => [
        'ez_a_laralve4' => [
            'connection' => 'main_connection',
            'name' => 'ez_a_laralve4',
            'attributes' => [
                'durable' => true,
                // 'exchange' => 'logs_topic',
                // 'exchange_type' => 'topic',
                // 'routing_key' => 'order.*',
            ],
        ]
    ],
    'publishers' => [],
    'consumers' => [
        'aConsumerName' => [
            'queue' => 'ez_a_laralve4',
            'message_processor' => \NeedleProject\LaravelRabbitMq\Processor\CliOutputProcessor::class
        ]
    ]
];
