# Open Longevity Genes Yii2 web app

API and CMS for open-genes.com

## Manual deployment
- Create a database
- Install Composer
- Create an .env file based on an example file in `app/common.env.sample` and write credits there
- Important notice: set local address to `127.0.0.1` instead of `localhost`

## Dev environment

```
docker-compose up -d --build
```

## Build backend
```
sh open-genes.sh up
```
Open [http://127.0.0.1:8080/](http://127.0.0.1:8080/)

DB wil be available at [localhost:3307](localhost:3307) root-secret

If you haven't got local .env file yet, copy it from .env.sample.

```
cp app/.env.sample app/.env
```

## Other

### Build styles
```
docker run --rm -ti -v $(pwd):/var/www catchdigital/node-sass npm run sass
```

Please check CONTRIBUTING.md for the information about deployment and development
