#!/bin/bash

#Change directory and change ownership
cd /var/www/html/ && sudo chown -R www-data:ubuntu external_service_provider/

#change directory and change permission
cd /var/www/html/ && sudo chmod -R 775 external_service_provider/
