#!/bin/sh

# The following is only needed when the volumes in docker-compose.yml are uncommented.
# We used to use chown here, but that broke when we switched to a different docker base image.
# Changing the permissions to 777 should work regardless of any future base image changes.
chmod 777 --quiet /var/www/html/temp /var/www/html/edocs

apache2-foreground