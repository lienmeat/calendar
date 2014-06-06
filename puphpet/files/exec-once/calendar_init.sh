#!/bin/bash
echo "* doing run once stuff for calendar!"
cd /var/www/dev/calendar2/app
sudo chmod -R a=rwx storage
cd /var/www/dev/calendar2
composer install