FROM gitpod/workspace-mongodb

RUN wget -q https://fastdl.mongodb.org/tools/db/mongodb-database-tools-ubuntu1804-x86_64-100.5.1.deb
RUN sudo apt-get -qq install ./mongodb-database-tools-*-100.5.1.deb

RUN wget -q https://downloads.mongodb.com/compass/mongodb-mongosh_1.1.6_amd64.deb
RUN sudo apt-get -qq install ./mongodb-mongosh_1.1.6_amd64.deb

RUN rm -fr mongodb-*

CMD ["mkdir -p /workspace/data && mongod --dbpath /workspace/data"]
