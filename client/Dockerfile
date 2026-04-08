FROM alpine:latest

RUN apk add --no-cache \
    bash \
    curl \
    wget \
    apache2-utils \
    iputils \
    net-tools \
    openssl

COPY client.sh /client.sh
RUN chmod +x /client.sh

CMD ["bin/bash"]
