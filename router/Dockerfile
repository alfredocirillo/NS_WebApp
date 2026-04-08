FROM alpine:latest

RUN apk add --no-cache iproute2 iptables

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
