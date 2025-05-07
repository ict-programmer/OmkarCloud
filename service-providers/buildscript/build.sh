#!/bin/bash 

#composer update

#cd /var/www/html/external_service_provider && composer update --ignore-platform-reqs



#swagger generation 
cd /var/www/html/external_service_provider && php artisan l5-swagger:generate 

#Optimizing cache update 

cd /var/www/html/external_service_provider && php artisan optimize:clear 


#Migration 

cd /var/www/html/external_service_provider && php artisan migrate 


#Optimizing cache update 

cd /var/www/html/external_service_provider && php artisan cache:clear

