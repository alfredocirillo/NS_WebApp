FROM alpine:latest

# Install SSH server and utilities
RUN apk add --no-cache \
    openssh \
    curl \
    wget \
    nano

# Create SSH directory
RUN mkdir -p /run/sshd

# Create a user for SSH access
RUN adduser -D -s /bin/sh handler && \
    echo "handler:password123" | chpasswd

# Allow root login (optional, for demo purposes)
RUN echo "PermitRootLogin yes" >> /etc/ssh/sshd_config && \
    echo "root:rootpass" | chpasswd

# Generate host keys (Alpine doesn't do this automatically)
RUN ssh-keygen -A

EXPOSE 22

CMD ["/usr/sbin/sshd", "-D"]