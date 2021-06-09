# Open Longevity Genes API

## Dev environment
_Note: to run this project you should checkout and run https://gitlab.com/open-genes/cms project first due to shared DB_

Build or rebuild backend
```
sh open-genes.sh build
```
Build & run backend
```
sh open-genes.sh up --build
```
Build & run backend, detached mode
```
sh open-genes.sh up -d --build
```
Run backend, detached mode
```
sh open-genes.sh up
```
Stop backend, detached mode
```
sh open-genes.sh down
```
Run backend, foreground mode
```
sh open-genes.sh up --no-detach
```
Build composer dependencies
```
docker run --rm -v $PWD/app:/app composer install
```

### Add to your /etc/hosts:
```
127.0.0.1 open-genes.develop cms.open-genes.develop
```

### Open http://open-genes.develop:8080/api

DB will be available at localhost:3307, user `root` pass `secret`

Enter php container:
```
docker ps
(copy hash of opengenes_php container)
docker exec -it (container_hash) bash
```
###Note: all the DB migrations should be at the CMS project

## Use xdebug

Build & run with xdebug enabled:
```
./open-genes.sh up --build xdebug
```

or ```./open-genes.sh up --build xdebug <your ip address>```
in case your ip address is not automatically detected by open-genes.sh

setup PHP Storm: File -> Setting, Languages & Frameworks -> PHP -> Debug -> DBGp Proxy
* Host: your host external ip, accessible from within php container
* Port: 9003

open-genes.sh detects xdebug ip address for eth0 interface as follows:
```
    ip -4 -br addr show eth0
```

Port 9003 is default one for xdebug v3 and it cannot be changed
