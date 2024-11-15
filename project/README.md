# Project Documentation

1. Clone/unzip the project
2. Run `docker-compose up -d` to start containers
3. Run `docker-compose exec backend composer install` to install dependencies
4. Run `docker-compose exec backend ./vendor/bin/phpunit --coverage-text` to run tests
5. Run `docker-compose exec db bash` then `mysql -u user -ppassword database` to check database