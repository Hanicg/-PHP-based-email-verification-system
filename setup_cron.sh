#!/bin/bash

# Get the absolute path to cron.php
CRON_PATH="$(pwd)/cron.php"

# Define the cron job entry (runs every day at 9:00 AM)
CRON_JOB="0 9 * * * php $CRON_PATH"

# Install the new CRON job, avoiding duplicates
( crontab -l 2>/dev/null | grep -v "$CRON_PATH" ; echo "$CRON_JOB" ) | crontab -

echo "✅ CRON job scheduled to run daily at 9:00 AM:"
echo "$CRON_JOB"
