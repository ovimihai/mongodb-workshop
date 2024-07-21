#!/usr/bin/env bash
echo $EXAMPLE_DBS_COUNT

for i in $(seq 1 $EXAMPLE_DBS_COUNT); do
    dbname="db${i}"
    echo $dbname
#    mongoimport --username admin --password adminabcd --authenticationDatabase admin --db $dbname --collection agenda agenda.json
#    mongoimport --username admin --password adminabcd --authenticationDatabase admin --db $dbname --collection adverts apartamente.json

#    mongorestore --username admin --password adminabcd --authenticationDatabase admin --db $dbname --collection speed /work/speed.bson
done


