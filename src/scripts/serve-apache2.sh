#!/bin/bash

opt=$1 # [--delete | -d] or [--create | -c]
map=$2
to=$3
port=$4

pathSiteAvailable="/etc/apache2/sites-available/"
siteAvailableMap="$pathSiteAvailable$map.conf"

case $opt in
	# Create virtual host
    -c | --create )	

		# Check if map exists
		if [ -e $siteAvailableMap ]
		then
			echo "This map [ $map ] already exists. Please Try Another one"
			exit;
		fi	
	
		# Check if directory exists or not
		if ! [ -d $to ]; then
			# Create the directory
			sudo mkdir -p $to > /dev/null
			# Give permission to root dir
			sudo chmod 755 $to

			# Create file index.php if not exist
			if  ! [ -e "$to/index.php" ]; then
				echo "<?php echo phpinfo(); " > $to/index.php
				echo $"Added content to $to/index.php"
			fi
		fi

		# Create virtual host rules file
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

		# Enable site map
		sudo a2ensite "$map.conf" > /dev/null && echo "Enabling site $map"

		# Restart Apache
		sudo service apache2 restart > /dev/null

	;;
	# Disable and delete all virtual host rules files
    -d | --delete )

        if [ "$map" == "" ]
        then
			for file in /etc/apache2/sites-available/*
			do
			  sudo a2dissite ${file##*/} > /dev/null
			  sudo rm -rf $file
			done
		else
			sudo a2dissite "$map.conf" > /dev/null
            sudo rm -rf $siteAvailableMap
		fi
    
	;;
	# Options invalid
    *) echo "Invalid option";;
esac
exit;





