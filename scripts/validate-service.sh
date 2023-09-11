#!/bin/bash

# Secrets
REGION="ap-northeast-1"

DBHostname="localhost"

DBPassword=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBPassword --with-decryption --query Parameters[0].Value)
DBPassword=$(echo $DBPassword | sed -e 's/^"//' -e 's/"$//')

DBUser=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBUser --query Parameters[0].Value)
DBUser=$(echo $DBUser | sed -e 's/^"//' -e 's/"$//')

DBName=$(aws ssm get-parameters --region $REGION --names /wordpress/db/DBName --query Parameters[0].Value)
DBName=$(echo $DBName | sed -e 's/^"//' -e 's/"$//')

SQL_COMMANDS=$(cat <<-EOF
    CREATE DATABASE ${DBName};
    USE ${DBName};
    CREATE TABLE userinfo (
        username VARCHAR(255) PRIMARY KEY,
        user_address VARCHAR(255) NOT NULL,
        user_phone_number VARCHAR(20) NOT NULL
    );
EOF
)
# Check if the database exists
if mysqlshow -h localhost -u$DBUser -p$DBPassword | grep -q $DBName; then
    echo "Database '$DBName' already exists. Doing nothing."
else
    # Connect to the RDS instance and execute SQL commands
    mysql -h$DBHostname -u$DBUser -p$DBPassword -e "$SQL_COMMANDS"

    # Check if the SQL execution was successful
    if [ $? -eq 0 ]; then
         echo "Database '$DBName' created, and schema applied successfully."
    else
        echo "Error: Unable to create database or apply schema."
    fi
fi