#!/usr/bin/env bash

set -euo pipefail

# Run all steps from the project root.
cd "$(dirname "${BASH_SOURCE[0]}")/../.."

bash bin/dev/generate-rabbitmq-definitions.sh
bash bin/dev/postgres.sh