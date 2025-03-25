<?php

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require_once __DIR__ . '/bootstrap.php';

try {
    $connection = connect('001');
    $channel = $connection->channel();

    // Новые продажи
    $exchange = 'x_sales';
    $routingKey = 'sale.001';
    $counter = 0;
    do {
        // Создаем сообщение
        $messageBody = json_encode([
            'product_id' => random_int(1, 60),
            'amount'     => round(random_int(100 * 100, 1000 * 100) / 100, 2)
        ], JSON_THROW_ON_ERROR);
        $message = new AMQPMessage(
            $messageBody,
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $channel->basic_publish($message, $exchange, $routingKey);

        echo " [x] Сообщение о новой продаже отправлено в обменник '{$exchange}'\n";


        $counter++;
    } while ($counter <= 10);

    // Заявки на обслуживание
    $exchange = 'x_tickets';
    $routingKey = 'ticket.001';
    $counter = 0;
    do {
        // Создаем сообщение
        $messageBody = json_encode([
            'description' => 'Сделать что-нибудь'
        ], JSON_THROW_ON_ERROR);
        $message = new AMQPMessage(
            $messageBody,
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $channel->basic_publish($message, $exchange, $routingKey);

        echo " [x] Сообщение о новом тикете отправлено в обменник '{$exchange}'\n";

        $counter++;
    } while ($counter <= 3);

    // Заявки на расходные материалы. Например, на воду.
    $exchange = 'x_expendables';
    $routingKey = 'ticket.001';
    $counter = 0;
    do {
        // Создаем сообщение
        $messageBody = json_encode([
            'amount' => random_int(5, 38)
        ], JSON_THROW_ON_ERROR);
        $properties = ['product' => 'water'];

        $message = new AMQPMessage(
            $messageBody,
            [
                'delivery_mode'       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'application_headers' => new AMQPTable($properties)
            ]
        );
        $channel->basic_publish($message, $exchange, $routingKey);

        echo " [x] Сообщение о новом тикете отправлено в обменник '{$exchange}'\n";

        $counter++;
    } while ($counter <= 5);

    $channel->close();
    $connection->close();
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
