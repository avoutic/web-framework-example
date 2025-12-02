#!/bin/bash

# Ensure we are in the project root
cd "$(dirname "$0")/.."

# Get database credentials from config (assuming default test values or env vars)
DB_HOST="${DATABASE_HOST:-127.0.0.1}"
DB_PORT="${DATABASE_PORT:-3399}"
DB_USER="${DATABASE_USER:-web_framework}"
DB_PASS="${DATABASE_PASSWORD:-testpassword}"
DB_NAME="${DATABASE_DATABASE:-web_framework}"

# Query to count jobs by queue name and status
QUERY="SELECT queue_name,
       COUNT(*) as total,
       SUM(CASE WHEN reserved_at IS NULL AND completed_at IS NULL THEN 1 ELSE 0 END) as pending,
       SUM(CASE WHEN reserved_at IS NOT NULL AND completed_at IS NULL THEN 1 ELSE 0 END) as processing,
       SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed,
       SUM(CASE WHEN error IS NOT NULL THEN 1 ELSE 0 END) as failed
       FROM jobs
       GROUP BY queue_name;"

echo "Job Queue Statistics:"
echo "------------------------------------------------------------------------"
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -t -e "$QUERY"
echo "------------------------------------------------------------------------"

