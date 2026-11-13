#!/bin/sh

set -eu pipefail

# Load variables from a trusted, shell-compatible .env file.
set -a
source .env
set +a

: "${RABBITMQ_USER:?RABBITMQ_USER is required}"
: "${RABBITMQ_PASSWORD:?RABBITMQ_PASSWORD is required}"
: "${DEBEZIUM_RABBITMQ_EXCHANGE:?DEBEZIUM_RABBITMQ_EXCHANGE is required}"
: "${DEBEZIUM_RABBITMQ_QUEUE:?DEBEZIUM_RABBITMQ_QUEUE is required}"
: "${DEBEZIUM_RABBITMQ_ROUTING_KEY:?DEBEZIUM_RABBITMQ_ROUTING_KEY is required}"

mkdir -p "$(dirname "$RABBITMQ_DEFINITIONS")"

jq -n '
{
    vhosts: [{
        name: "/"
    }],
    users: [{
        name: env.RABBITMQ_USER,
        password: env.RABBITMQ_PASSWORD,
        tags: ["administrator"]
    }],
    permissions: [{
        user: env.RABBITMQ_USER,
        vhost: "/",
        configure: ".*",
        write: ".*",
        read: ".*"
    }],
    exchanges: [{
        name: env.DEBEZIUM_RABBITMQ_EXCHANGE,
        vhost: "/",
        type: "topic",
        durable: true,
        auto_delete: false,
        internal: false,
        arguments: {}
    }],
    queues: [{
        name: env.DEBEZIUM_RABBITMQ_QUEUE,
        vhost: "/",
        durable: true,
        auto_delete: false,
        arguments: {}
    }],
    bindings: [{
        source: env.DEBEZIUM_RABBITMQ_EXCHANGE,
        vhost: "/",
        destination: env.DEBEZIUM_RABBITMQ_QUEUE,
        destination_type: "queue",
        routing_key: env.DEBEZIUM_RABBITMQ_ROUTING_KEY,
        arguments: {}
    }]
}
' > $RABBITMQ_DEFINITIONS

echo "RabbitMQ definitions successfully generated."