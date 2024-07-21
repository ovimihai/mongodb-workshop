FROM mongo:6.0.8


RUN apt-get update &&\
    apt-get install -y curl wget

RUN mkdir -p /work/
WORKDIR /work/

RUN chmod 777 -R /work