#!/bin/bash 

#composer update

#cd /var/www/html/felidae-network/service-providers && composer update --ignore-platform-reqs



#swagger generation 
cd /var/www/html/felidae-network/service-providers && php artisan l5-swagger:generate 

#Optimizing cache update 

cd /var/www/html/felidae-network/service-providers && php artisan optimize:clear 


#Migration 

cd /var/www/html/felidae-network/service-providers && php artisan migrate 


#Optimizing cache update 

cd /var/www/html/felidae-network/service-providers && php artisan cache:clear

