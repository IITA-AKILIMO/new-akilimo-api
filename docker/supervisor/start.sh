#!/bin/bash

# Create required directories
mkdir -p /var/log/supervisor

# Start supervisord
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
