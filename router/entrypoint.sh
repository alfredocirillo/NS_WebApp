#!/bin/sh

# Il forwarding è già abilitato dal docker-compose (sysctls), 
# ma se vuoi esserne sicuro, le policy IPTables faranno il resto.

# Resetta le regole (buona pratica)
iptables -F
iptables -t nat -F

# Imposta le Policy di default (Drop per la sicurezza)
iptables -P FORWARD DROP
iptables -P INPUT DROP
iptables -P OUTPUT ACCEPT

# Permetti traffico su Loopback
iptables -A INPUT -i lo -j ACCEPT
iptables -A INPUT -p icmp -j ACCEPT

# --- REGOLE DI FORWARDING TRA LE RETI ---

## 1. Permetti il traffico già stabilito (indispensabile per le risposte)
iptables -A FORWARD -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT

# 2. Permetti HTTP (TCP 80) dai Client al Webserver (senza vincoli di interfaccia)
iptables -A FORWARD -p tcp --dport 80 -s 10.10.0.0/24 -d 10.20.0.10 -j ACCEPT

# 2b. Permetti HTTPS (TCP 443) dai Client al Webserver
iptables -A FORWARD -p tcp --dport 443 -s 10.10.0.0/24 -d 10.20.0.10 -j ACCEPT

# 3. Permetti ICMP (Ping) in entrata verso il Webserver
iptables -A FORWARD -p icmp -s 10.10.0.0/24 -d 10.20.0.10 -j ACCEPT

# 4. Permetti ICMP (Ping) in uscita dal Webserver (per le risposte Echo Reply)
iptables -A FORWARD -p icmp -s 10.20.0.10 -d 10.10.0.0/24 -j ACCEPT

echo "[+] Router rules applied and ready!"
# Mantiene il container in vita
tail -f /dev/null
