# Open Genes API (legacy)

## Contents
- Local build
- Common questions
- Troubleshooting

## Local build

Make sure you have an installed Docker on your system.

### 1. Clone and run CMS project
Clone [open-genes-cms](https://github.com/open-genes/open-genes-cms) repository, follow the instructions.

### 2. Create local .env file
In **/app** directory create a new file called **.env**. Copy **/.env.sample** contents into it.

### 3. Build local image
Run **./open-genes-backend.sh** with one of the following commands:

#### Build or rebuild backend
```
sh open-genes.sh build
```
#### Build & run backend
```
sh open-genes.sh up --build
```
#### Build & run backend, detached mode
```
sh open-genes.sh up -d --build
```
#### Run backend, detached mode
```
sh open-genes.sh up
```
#### Stop backend, detached mode
```
sh open-genes.sh down
```
#### Run backend, foreground mode
```
sh open-genes.sh up --no-detach
```
#### Build composer dependencies
```
docker run --rm -v $PWD/app:/app composer install
```

### 4. Add an entry to your hosts:

Add this entry to your **/etc/hosts** (Debian) or an equivalent:

```
127.0.0.1 open-genes.develop cms.open-genes.develop
```

### Open http://open-genes.develop:8080/api

DB will be available on http://localhost:3307 <br>
user `root` <br>
password `secret`

## Common questions

### How to access PHP container:

```
docker ps
(copy hash of opengenes_php container)
docker exec -it (container_hash) bash
```
> Note: all the DB migrations should be made at the CMS project

### How to use xdebug

#### 1. Build & run with xdebug enabled:
```
./open-genes.sh up --build xdebug
```

or ```./open-genes.sh up --build xdebug <your ip address>```
in case your ip address is not automatically detected by open-genes.sh

#### 2. Setup PHP Storm: https://blog.denisbondar.com/post/phpstorm_docker_xdebug

open-genes.sh detects xdebug ip address as follows:
```
    ip -4 -br addr show | grep "$CLIENT_HOST"
```

Port **9003** is default for xdebug v3, and it cannot be changed.

## Troubleshooting

### "open-genes.sh: line 2: UID: readonly variable" error
if your operating system differs from Debian/Ubuntu Linux family, please consider this solution. 
In **/open-genes.sh** file replace `UID` constant name to `XUID` then run the script again.
