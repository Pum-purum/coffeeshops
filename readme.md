### Демонстрация работы сети кофеен со связями между вовлеченными компаниями через RabbitMQ

Есть множество точек по продаже кофе. Каждая точка нумеруется 001, 002, 003 и так далее. Есть отдел продаж, в который каждая точка отправляет данные о каждой новой продаже. Есть компании, обслуживающие точки с точки зрения мелкого бытового ремонта: Julia и Samantha. Есть компании-поставщики расходных материалов: кофе, воды, бумажной упаковки и т.п.

В этом проекте демонстрируется процесс обмена сообщениями между отделами и компаниями франшизы.

####  Как это все запустить

Генерируем корневые ключ и сертификат:
```bash
openssl genrsa -out ca/CAKey.pem 4096 &&
openssl req -x509 -new -key ca/CAKey.pem -sha512 -days 365 -out crt/CACert.pem -subj '/CN=root certificate/O=CoffeeeShops Inc.'
```

Генерируем сертификаты для авторизации пользователей:

```bash
./generator.sh -n Server -c rabbitmq -o 'Server' &&
./generator.sh -n 001 -c 001 -o 'CoffeeShop #001' &&
./generator.sh -n maintainer -c maintainer -o 'Samantha Industry, LLC' &&
./generator.sh -n sales_management -c sales_management -o 'CoffeeShops Inc.' &&
./generator.sh -n supplier -c supplier -o 'Food & Drinks Izhevsk, LLC'
```

Собираем контейнеры и стартуем:

```bash
docker-compose build && docker-compose up -d
```

#### Сценарии работы:
Для начала заходим в контейнер php:
```bash
docker exec -it coffeeshops-php-1 bash
cd scripts
```

Точка 001 сообщает о новых продажах, делает несколько заявок на обслуживание и расходные материалы:
```bash
php 001.php
```

Отдел продаж считывает сообщения о продажах:
```bash
php sales_management.php
```

Компания Julia считывает заявки на обслуживание:
```bash
php julia.php
```

Поставщик воды считывает заявки на воду:
```bash
php water_supplier.php
```

После отработки всех скриптов увидим отчеты о считывании сообщений в output.
Не все очереди считываются, это сделано намеренно для упрощения проекта.
