#!/bin/bash
set -x

sudo yum install -y mariadb-server 
sudo systemctl enable mariadb
sudo systemctl start mariadb

RootPassword="password"
# Set Mariadb Root Password
sudo mysqladmin -u root password $RootPassword 

# MySQL credentials
REGION="ap-northeast-1"

DBPassword=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBPassword --with-decryption --query Parameters[0].Value)
DBPassword=$(echo $DBPassword | sed -e 's/^"//' -e 's/"$//')

DBUser=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBUser --query Parameters[0].Value)
DBUser=$(echo $DBUser | sed -e 's/^"//' -e 's/"$//')

#!/bin/bash

# Log in to MySQL as the root user and create the new user, grant privileges, and flush privileges
mysql -u root -p"${RootPassword}" -e "CREATE USER '${DBUser}'@'localhost' IDENTIFIED BY '${DBPassword}'; GRANT ALL PRIVILEGES ON *.* TO '${DBUser}'@'localhost'; FLUSH PRIVILEGES;"

echo "New user '${DBUser}' created with global privileges."


