### Multiple choices test checker
#### Install
- `cd /var/www && git clone https://github.com/IsakovIgor/Multiple-choices-test`
(you can use another path for project instead of /var/www)
- `cd multiple-choices-test`
- `cp .env.dist .env` and choose port for psql if 5432 is locked (don't forget about EXTERNAL_PSQL_PORT)
- choose EXTERNAL_NGINX_PORT if 8080 is locked in your pc
- `docker compose up -d` or `docker-compose up -d` for old docker versions
- `docker compose exec multiple-choices-test sh`
- `php ./bin/console doctrine:migrations:migrate`
- `php ./bin/console app:db:fill`
- `php ./vendor/bin/phpunit` for testing
- `exit`

#### Usage
- go to `127.0.0.1:8080/test`