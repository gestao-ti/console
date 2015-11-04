#!/bin/bash

map=$1
to=$2
port=$3

pathSiteEnable="/etc/apache2/sites-enabled/"
pathSiteAvailable="/etc/apache2/sites-available/"
siteAvailableMap="$sitesAvailable$map.conf"

### check if domain already exists
if [ -e $siteAvailableMap ]; then
	### disable map
	sudo a2dissite "$map.conf" > /dev/null && echo "Site $map disabled"
	### restart Apache
	sudo service apache2 restart > /dev/null
	### Delete virtual host rules files
	sudo rm -rf $siteAvailableMap
fi

### check if directory exists or not
if ! [ -d $to ]; then
	### create the directory
	sudo mkdir -p $to
	### give permission to root dir
	sudo chmod 755 $to

	# Create file index.php if not exist
	if  ! [ -e "$to/index.php" ]; then
		echo "<?php echo phpinfo(); " > $to/index.php
		echo $"Added content to $to/index.php"
	fi
fi

### create virtual host rules file
if ! sudo echo "
	<VirtualHost *:$port>
		ServerAdmin webmaster@localhost
		ServerName $map
		ServerAlias $map
		DocumentRoot $to
		<Directory />
			AllowOverride All
		</Directory>
		<Directory $to>
			Options Indexes FollowSymLinks MultiViews
			AllowOverride all
			Require all granted
		</Directory>
		ErrorLog /var/log/apache2/$map-error.log
		LogLevel error
		CustomLog /var/log/apache2/$map-access.log combined
	</VirtualHost>" > $siteAvailableMap
then
	echo -e $"There is an ERROR creating $map file"
	exit;
else
	echo -e $"http://$map => $to"
fi

### enable site map
sudo a2ensite "$map.conf" > /dev/null && echo "Enabling site $map"

### restart Apache
sudo service apache2 restart > /dev/null
exit;




