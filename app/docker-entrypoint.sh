#!/bin/sh

# These permissions are set here instead of in Dockerfile so that they run after the volumes are mounted.
chown www-data /var/www/html/temp /var/www/html/edocs
apache2-foreground