<?php

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/bootstrap.php';

$queue = 'q_sales';

try {
    $connection = connect('sales_management');
    $channel = $connection->channel();

    $callback = static function (AMQPMessage $msg) {
        $logFile = '/app/output/sales.log';
        $messageBody = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $product = $messageBody['product_id'];
        $amount = $messageBody['amount'];
        $point = explode('.', $msg->getRoutingKey())[1];
        $timestamp = date('Y-m-d H:i:s');

        // Формируем строку для лога
        $logEntry = "[{$timestamp}] Новая продажа от точки {$point} товара {$product} на сумму {$amount}\n";

        // Записываем в лог-файл
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        echo " [x] Новая продажа записана в лог\n";

        // Подтверждаем обработку сообщения
        $msg->ack();
    };

    $channel->basic_consume(
        $queue,
        '',
        false,
        false,
        false,
        false,
        $callback
    );

    // 6. Бесконечный цикл ожидания сообщений
    while ($channel->is_consuming()) {
        $channel->wait();
    }

    // 7. Закрываем соединение (этот код выполнится только при ошибке)
    $channel->close();
    $connection->close();
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
