#!/bin/bash

echo "0 0 * * * /usr/bin/php /home/docker/php/cron.php > /dev/null 2>&1" >> /etc/crontab

/etc/init.d/cron restart
