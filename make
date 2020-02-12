#!/bin/bash
set -x;
export APP_ENV=prod APP_DEBUG=0;
git pull;
sudo /bin/systemctl reload php7.4-fpm;
composer dump-env prod;
composer install --no-dev --optimize-autoloader --classmap-authoritative --apcu-autoloader;
./bin/console doctrine:cache:clear-metadata;
./bin/console doctrine:migration:migrate -n ;
./bin/console doctrine:cache:clear-metadata;

./bin/console cache:clear --no-warmup;
./bin/console cache:warmup;
./bin/console cache:pool:prune;
./bin/console cache:pool:clear cache.app cache.system;
./bin/console doctrine:cache:clear-entity-region --all;
./bin/console doctrine:cache:clear-collection-region --all;
./bin/console doctrine:cache:clear-result;
./bin/console doctrine:cache:clear-query --flush;

./bin/console messenger:stop-workers ;
systemctl --user daemon-reload;
./bin/console assets:install;
crontab < ./.config/crontab;