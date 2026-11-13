#!/usr/bin/env bash

set -euo pipefail

# Load variables from the project root.
set -a
source .env
set +a

: "${POSTGRES_DEBEZIUM_SQL:?POSTGRES_DEBEZIUM_SQL is required}"
: "${DEBEZIUM_POSTGRES_USER:?DEBEZIUM_POSTGRES_USER is required}"
: "${DEBEZIUM_POSTGRES_PASSWORD:?DEBEZIUM_POSTGRES_PASSWORD is required}"
: "${DEBEZIUM_POSTGRES_SCHEMA:?DEBEZIUM_POSTGRES_SCHEMA is required}"
: "${DEBEZIUM_POSTGRES_TABLE:?DEBEZIUM_POSTGRES_TABLE is required}"
: "${DEBEZIUM_PUBLICATION_NAME:?DEBEZIUM_PUBLICATION_NAME is required}"

# Split the pipe-separated list, preserving trailing empty entries for validation.
IFS='|' read -r -a tables <<< "${DEBEZIUM_POSTGRES_TABLE}|"

tables_sql=""
separator=""

for table in "${tables[@]}"; do
    # Remove surrounding whitespace from each table name.
    table="${table#"${table%%[![:space:]]*}"}"
    table="${table%"${table##*[![:space:]]}"}"

    if [[ -z "$table" ]]; then
        echo "DEBEZIUM_POSTGRES_TABLE contains an empty table name." >&2
        exit 1
    fi

    tables_sql+="${separator}\"${DEBEZIUM_POSTGRES_SCHEMA}\".\"${table}\""
    separator=", "
done

# Create the parent directory if it does not exist.
mkdir -p "$(dirname "$POSTGRES_DEBEZIUM_SQL")"

# Generate SQL with values substituted from .env.
cat > "$POSTGRES_DEBEZIUM_SQL" <<SQL
-- Create the replication role only if it does not exist.
SELECT format(
    'CREATE ROLE %I WITH LOGIN REPLICATION',
    '${DEBEZIUM_POSTGRES_USER}'
)
WHERE NOT EXISTS (
    SELECT 1
    FROM pg_roles
    WHERE rolname = '${DEBEZIUM_POSTGRES_USER}'
)
\gexec

-- Reconcile credentials and replication privileges on every run.
ALTER ROLE "${DEBEZIUM_POSTGRES_USER}"
    WITH LOGIN REPLICATION PASSWORD '${DEBEZIUM_POSTGRES_PASSWORD}';

-- Grant access to the database in which this script is running.
SELECT format(
    'GRANT CONNECT ON DATABASE %I TO %I',
    current_database(),
    '${DEBEZIUM_POSTGRES_USER}'
)
\gexec

-- Grant access to the configured schema.
GRANT USAGE ON SCHEMA "${DEBEZIUM_POSTGRES_SCHEMA}"
    TO "${DEBEZIUM_POSTGRES_USER}";

-- Grant read access to every selected table.
GRANT SELECT ON TABLE ${tables_sql}
    TO "${DEBEZIUM_POSTGRES_USER}";

-- Create the publication only if it does not exist.
SELECT format(
    'CREATE PUBLICATION %I',
    '${DEBEZIUM_PUBLICATION_NAME}'
)
WHERE NOT EXISTS (
    SELECT 1
    FROM pg_publication
    WHERE pubname = '${DEBEZIUM_PUBLICATION_NAME}'
)
\gexec

-- Set the complete list of published tables.
ALTER PUBLICATION "${DEBEZIUM_PUBLICATION_NAME}"
    SET TABLE ${tables_sql};
SQL