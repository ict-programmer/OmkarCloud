#!/bin/bash

#Change directory and change ownership
cd /var/www/html/ && sudo chown -R www-data:ubuntu felidae-network/

#change directory and change permission
cd /var/www/html/ && sudo chmod -R 775 felidae-network/
