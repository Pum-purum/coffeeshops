<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;



function connect(string $asUser): AMQPSSLConnection {
    try {
        // Настройки подключения
        $host = 'rabbitmq';
        $port = 5671;
        $user = '';
        $pass = '';
        $vhost = '/';

        // Пути к сертификатам
        $sslOptions = [
            'cafile'           => '/app/crt/CACert.pem',
            'local_cert'       => '/app/crt/'.$asUser.'Cert.pem',
            'local_pk'         => '/app/crt/'.$asUser.'Key.pem',
            'verify_peer'      => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false
        ];

        $options = [
            'login_method' => 'EXTERNAL'
        ];

        return new AMQPSSLConnection(
            $host,
            $port,
            $user,
            $pass,
            $vhost,
            $sslOptions,
            $options
        );
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage() . "\n";
    }
}



