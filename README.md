# NS_WebApp

Docker demo on the potential NetSec consequences of letting a generative AI build you a web service


## Directory Overview:
- `web 172.18.0.11`: contains all the webapp's files
- `handler`: represents the machine used to remotely handle the webserver
- `client`: represents the machine of random user making requests to the webapp
- `web_evil 172.18.0.10`: website used by the attacker to exploit CSRF



## Docker Commands

To start all 3 docker machines, the network and the database:
```
docker-compose up --build
```

To stop and clear the volumes contents: 
```
docker-compose down -v
```

To delete all containers and the images downloaded:
```
docker system prune -a
```
