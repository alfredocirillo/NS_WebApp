# NS_WebApp

Docker demo on the potential NetSec consequences of letting a generative AI build you a web service

This `main` branch has all vulnerabilities still implemented.

## Directory Overview:
- `web @ 10.20.0.10`: contains all the webapp's files, created by Claude Haiku 4.5
- `handler`: represents the machine used to remotely handle the webserver
- `client`: generic user making requests to the webapp
- `web_evil @ 10.10.0.10`: attacker's site to exploit CSRF on "web"
- `mitm`: for the attaccker to spoof the connection between "client" and "web" and sniff their communication


## Various Commands

To start all 3 docker machines, the network and the database:
```
docker-compose up --build
```

To stop and clear the volumes' contents: 
```
docker-compose down -v
```

To delete all containers and the images downloaded:
```
docker system prune -a
```

To sniff HTTP traffic from inside the mitm container
```
tcpdump -i eth0 -A -s 0 'tcp port 80'
```
