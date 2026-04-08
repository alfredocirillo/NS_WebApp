FROM debian:bookworm-slim

RUN apt update && apt install -y --no-install-recommends \
    iproute2 \
    dsniff \
    tcpdump \
    && rm -rf /var/lib/apt/lists/*

CMD ["/bin/sh"]

