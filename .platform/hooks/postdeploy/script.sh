#!/bin/bash
sudo su
curl https://packages.microsoft.com/config/rhel/9.0/prod.repo > /etc/yum.repos.d/mssql-release.repo
exit
sudo yum remove unixODBC-utf16 unixODBC-utf16-devel
sudo ACCEPT_EULA=Y yum install -y msodbcsql18
sudo ACCEPT_EULA=Y yum install -y mssql-tools18
echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc
source ~/.bashrc
sudo yum install -y unixODBC-devel