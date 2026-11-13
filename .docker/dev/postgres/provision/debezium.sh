#!/bin/sh

set -eu

: "${DEBEZIUM_POSTGRES_USER:?DEBEZIUM_POSTGRES_USER is required}"
: "${DEBEZIUM_POSTGRES_PASSWORD:?DEBEZIUM_POSTGRES_PASSWORD is required}"
: "${DEBEZIUM_POSTGRES_DBNAME:?DEBEZIUM_POSTGRES_DBNAME is required}"
: "${DEBEZIUM_POSTGRES_SCHEMA:?DEBEZIUM_POSTGRES_SCHEMA is required}"
: "${DEBEZIUM_POSTGRES_TABLE:?DEBEZIUM_POSTGRES_TABLE is required}"
: "${DEBEZIUM_PUBLICATION_NAME:?DEBEZIUM_PUBLICATION_NAME is required}"

export PGPASSWORD="${POSTGRES_PASSWORD:-}"

# Connect as the database administrator to provision the CDC account.
exec psql \
    --no-psqlrc \
    --no-password \
    --username="${POSTGRES_USER:-postgres}" \
    --dbname="${DEBEZIUM_POSTGRES_DBNAME}" \
    --port="${DEBEZIUM_POSTGRES_PORT:-5432}" \
    --set=ON_ERROR_STOP=1 \
    --single-transaction \
    --file=/provision/debezium.sql