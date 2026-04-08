# NS_WebApp

Docker demo on the potential NetSec consequences of letting a generative AI build you a web service


## Directory Overview:
- `web`: contains all the webapp's files
- `handler`: represents the machine used to remotely handle the webserver
- `client`: represents the machine of random user making requests to the webapp
- `web_evil`: attacker's site to exploit CSRF on "web"
- `mitm`: for the attaccker to spoof the connection between "client" and "web" and sniff their communication


## Various Commands

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

To sniff the traffic from inside the mitm container
```
tcpdump -i eth0 -A -s 0 'tcp port 80'
```
