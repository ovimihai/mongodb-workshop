#!/usr/bin/env bash

CURRENT_DIR=$(dirname $(realpath $0 ))
cd $CURRENT_DIR

docker build -t workshop-mongo-dbs .

docker compose down -v

docker compose up -d