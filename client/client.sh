#!/bin/bash

# Simulates a user logging in repeatedly
while true; do
    sleep 15;

    curl http://10.20.0.10:80/index.php

    sleep 5;

    curl http://10.20.0.10:80/login.php

    sleep 5;

    curl -d "username=jane_smitt&password=jane" http://10.20.0.10:80/login.php

    sleep 5;

    curl -d "username=jane_smith&password=jane" http://10.20.0.10:80/login.php
done