FROM rabbitmq:4.0.6-management

COPY ./rabbit/definitions.json /etc/rabbitmq
COPY ./rabbit/rabbitmq.conf /etc/rabbitmq
COPY crt /etc/pki/tls

RUN rabbitmq-plugins enable rabbitmq_auth_mechanism_ssl
