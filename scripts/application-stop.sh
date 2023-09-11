#!/bin/bash
set -x

# System control will return either "active" or "inactive".
httpd_running=$(systemctl is-active httpd)
if [ "$httpd_running" == "active" ]; then
    sudo systemctl stop httpd
fi

sudo systemctl disable mariadb
sudo systemctl stop mariadb
sudo yum remove mariadb-server mariadb-client mariadb -y
sudo yum autoremove -y
sudo yum clean all
sudo rm -rf /var/lib/mysql
sudo rm -rf /etc/my.cnf
