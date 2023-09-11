#!/bin/bash
set -x

# env setup
# REGION="ap-northeast-1"

# DBPassword=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBPassword --with-decryption --query Parameters[0].Value)
# DBPassword=$(echo $DBPassword | sed -e 's/^"//' -e 's/"$//')

# DBUser=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBUser --query Parameters[0].Value)
# DBUser=$(echo $DBUser | sed -e 's/^"//' -e 's/"$//')

# DBName=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBName --query Parameters[0].Value)
# DBName=$(echo $DBName | sed -e 's/^"//' -e 's/"$//')

# DBEndpoint=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBEndpoint --query Parameters[0].Value)
# DBEndpoint=$(echo $DBEndpoint | sed -e 's/^"//' -e 's/"$//')

# DBHostname=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBHostname --query Parameters[0].Value)
# DBHostname=$(echo $DBHostname | sed -e 's/^"//' -e 's/"$//')

# EFSID=$(aws ssm get-parameters --region $REGION --names /wordpress/efs/EFSID --query Parameters[0].Value)
# EFSID=$(echo $EFSID | sed -e 's/^"//' -e 's/"$//')

# # create .env file inside /var/www/html
# echo DBHostname="$DBHostname" > /var/www/html/.env
# echo DBUser="$DBUser" >> /var/www/html/.env
# echo DBName="$DBName" >> /var/www/html/.env
# echo DBPassword="$DBPassword" >> /var/www/html/.env

# dependency to read phpdotenv file
composer require vlucas/phpdotenv --working-dir=/var/www/html

sudo sh -c 'echo "Hello, CodeDeploy" > /var/www/html/index.html'

# change permissions 
# sudo chown -R ec2-user:apache /var/www/

