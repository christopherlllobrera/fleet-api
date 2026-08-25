#!/usr/bin/env bash
# AWS Elastic Beanstalk Post-Deploy Hook
# Ensures correct permissions for storage and bootstrap/cache

sudo chown -R webapp:webapp /var/app/current/storage /var/app/current/bootstrap/cache
sudo chmod -R 775 /var/app/current/storage /var/app/current/bootstrap/cache

# Run Artisan commands as the webapp user
sudo -u webapp php /var/app/current/artisan config:cache
sudo -u webapp php /var/app/current/artisan route:cache
sudo -u webapp php /var/app/current/artisan view:cache
