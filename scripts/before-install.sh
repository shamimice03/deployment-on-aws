#!/bin/bash
set -x

# System Updates
sudo yum update -y
sudo yum upgrade -y

# Install the application dependencies you need for WordPress
sudo yum install -y httpd wget 
sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2

# Download and install Composer globally
sudo curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install the Amazon EFS utilities
sudo yum -y install amazon-efs-utils

# Start and enable Webserver
sudo systemctl enable httpd
sudo systemctl start httpd