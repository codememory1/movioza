#!/bin/sh

set -eu

# Run all steps from the project root.
cd "$(dirname "${BASH_SOURCE[0]}")/../.."

bash bin/dev/generate-debezium-sql.sh

docker compose -f docker-compose-dev.yaml exec -T postgres \
  sh /provision/debezium.sh