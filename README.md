# NS_WebApp

Docker demo on the potential NetSec consequences of letting a generative AI build you a web service


## Overview:
- `web` contains all the webapp's files
- `handler` is a fictional admin's machine
- `client` is a random user making request to the webapp


## Docker Commands

To start all 3 docker machines, the network and the database:
```
docker-compose up --build
```

To stop: `CTRL+C` or `docker-compose down -v` to also remove the database images.

To delete all containers and the images downloaded:
```
docker system prune -a
```
