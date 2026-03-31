FROM ubuntu:22.04

# Update and install SSH server
RUN apt-get update && apt-get install -y \
    openssh-server \
    curl \
    wget \
    nano \
    && rm -rf /var/lib/apt/lists/*

# Create SSH directory and configure
RUN mkdir -p /run/sshd

# Create a user for SSH access
RUN useradd -m -s /bin/bash handler && \
    echo "handler:password123" | chpasswd

# Allow root login (optional, for demo purposes)
RUN echo "PermitRootLogin yes" >> /etc/ssh/sshd_config && \
    echo "root:rootpass" | chpasswd

EXPOSE 22

CMD ["/usr/sbin/sshd", "-D"]
