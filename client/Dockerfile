FROM ubuntu:22.04

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    apache2-utils \
    iputils-ping \
    net-tools \
    && rm -rf /var/lib/apt/lists/*

CMD ["/bin/bash"]