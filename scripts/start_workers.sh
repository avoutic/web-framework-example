#!/bin/bash

# Ensure we are in the project root
cd "$(dirname "$0")/.."

echo "Starting workers..."

# Start 3 workers for 'tasks' queue
./framework queue:worker tasks &
PID1=$!
echo "Started worker for tasks (PID: $PID1)"

./framework queue:worker tasks &
PID2=$!
echo "Started worker for tasks (PID: $PID2)"

./framework queue:worker tasks &
PID3=$!
echo "Started worker for tasks (PID: $PID3)"

# Start 1 worker for 'mails' queue
./framework queue:worker mails &
PID4=$!
echo "Started worker for mails (PID: $PID4)"

echo "Workers started."

# Setup cleanup function
cleanup() {
    echo "Stopping workers..."
    kill $PID1 $PID2 $PID3 $PID4
    wait
    echo "Workers stopped."
}

# Trap SIGINT (Ctrl+C)
trap cleanup SIGINT

# Wait for all background processes
wait

