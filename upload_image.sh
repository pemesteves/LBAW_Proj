#!/bin/bash

# Stop execution if a step fails
set -e

DOCKER_USERNAME=lbaw2034 # Replace by your docker hub username
IMAGE_NAME=lbaw2034                 # Replace with your group's image name

# Ensure that dependencies are available
composer install
php artisan clear-compiled
php artisan optimize

docker build -t $DOCKER_USERNAME/$IMAGE_NAME . --no-cache
docker push $DOCKER_USERNAME/$IMAGE_NAME
