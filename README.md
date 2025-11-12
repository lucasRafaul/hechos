
composer install 

symfony server:start

php bin/console doctrine:schema:update --force

modificar .env

php.ini -> habilitar .gd y .zip

php bin/console doctrine:fixtures:load


php bin/console doctrine:fixtures:load --no-interaction