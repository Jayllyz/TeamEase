#!/bin/bash
set -e

# remove default nginx html folder
rm -rf /usr/share/nginx/html/*

# set permission
chmod 755 /home/php

# install dependencies
apt-get update
apt-get install -y cron certbot python3-certbot-nginx bash wget

# get SSL certificates
certbot certonly --agree-tos -m "antonydavid945@gmail.com" -n -d "www.togetherandstronger.site,togetherstronger.site" --webroot -w /home/php

# remove apt cache
rm -rf /var/lib/apt/lists/*

# set up cron job to renew SSL certificates
echo "PATH=$PATH" > /etc/cron.d/certbot-renew
echo "@monthly certbot renew --nginx >> /var/log/cron.log 2>&1" >> /etc/cron.d/certbot-renew
crontab /etc/cron.d/certbot-renew

echo "start nginx and cron"

# start nginx and cron
cron && nginx -g 'daemon off;'
