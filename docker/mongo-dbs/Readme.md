# Setup students Mongo instances

Deploy one mongodb instance with up to 10 users, each with a different database to work with

## Clone repo, switch branch and run the setup

```bash
git clone https://github.com/ovimihai/mongodb-workshop.git
cd mongodb-workshop
git checkout workshop
cd docker/mongo-dbs
bash setup.sh
```

## Connect
Connect to the instance using strings like this:
`mongodb://user2:pass2@$HOSTNAME:27100/?authSource=db2`
