#!/bin/bash 

# composer update
cd /var/www/html/felidae-network/service-providers && composer update --ignore-platform-reqs


# Optimizing cache update 
cd /var/www/html/felidae-network/service-providers && php artisan optimize:clear 
cd /var/www/html/felidae-network/service-providers && php artisan cache:clear
cd /var/www/html/felidae-network/service-providers && php artisan view:clear
cd /var/www/html/felidae-network/service-providers && php artisan route:clear
cd /var/www/html/felidae-network/service-providers && php artisan config:cache

# swagger generation 
cd /var/www/html/felidae-network/service-providers && php artisan l5-swagger:generate 

# Migration 
#cd /var/www/html/felidae-network/service-providers && php artisan migrate 



