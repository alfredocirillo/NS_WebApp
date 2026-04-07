#!/bin/bash

# Simulates a user logging in repeatedly
while true; do
    echo "[*] Logging in as alice..."
    curl -s -c /tmp/cookies.txt -b /tmp/cookies.txt \
         -d "username=alice&password=SecurePassword123" \
         http://172.20.0.10/index.php > /dev/null
    
    echo "[*] Accessing dashboard..."
    curl -s -b /tmp/cookies.txt http://172.20.0.10/dashboard.php >
