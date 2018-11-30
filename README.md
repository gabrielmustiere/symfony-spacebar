# Spacebar Installation

```
composer install
php bin/console doctrine:database:create --if-no-exists -q
php bin/console doctrine:migration:migrate -q
php bin/console doctrine:fixtures:load -q
php bin/console server:run
```
