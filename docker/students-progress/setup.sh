#!/usr/bin/env bash

export CURRENT_DIR=$(dirname $(realpath $0 ))

echo $CURRENT_DIR

docker compose down

docker compose up -d

