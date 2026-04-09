#!/bin/bash

# Simulates a user logging in repeatedly
while true; do
    sleep 15;

    curl -k https://10.20.0.10:443/index.php

    sleep 5;

    curl -k https://10.20.0.10:443/login.php

    sleep 5;

    curl -k -d "username=jane_smitt&password=jane" https://10.20.0.10:443/login.php

    sleep 5;

    curl -k -d "username=jane_smith&password=jane" https://10.20.0.10:443/login.php
done
