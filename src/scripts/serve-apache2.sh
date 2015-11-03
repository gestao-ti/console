#!/bin/bash

### Set default parameters
action=$1
map=$2
to=$3
owner=$(who am i | awk '{print $1}')
email='webmaster@localhost'
pathSiteEnable='/etc/apache2/sites-enabled/'
pathSiteAvailable='/etc/apache2/sites-available/'
siteAvailableMap=$sitesAvailable$map.conf

if [ "$action" == 'create' ]
	then
		### check if domain already exists
		if [ -e $siteAvailableMap ]; then
			echo -e $"Domain $map already exists."
			exit;
		fi

		### check if directory exists or not
		if ! [ -d $to ]; then
			### create the directory
			mkdir -p $to
			### give permission to root dir
			chmod 755 $to
			### write test file in the new domain dir
			if ! echo "<?php echo phpinfo(); ?>" > $to/phpinfo.php
			then
				echo $"ERROR: Not able to write in file $to/index.php. Please check permissions"
				exit;
			else
				echo $"Added content to $to/index.php"
			fi
		fi

		### create virtual host rules file
		if ! echo "
		<VirtualHost *:80>
			ServerAdmin $email
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
			echo -e $"\nNew Virtual Host Created\n"
		fi



		if [ "$owner" == "" ]; then
			chown -R $(whoami):$(whoami) $to
		else
			chown -R $owner:$owner $to
		fi

		### enable website
		a2ensite $map

		### restart Apache
		/etc/init.d/apache2 reload

		### show the finished message
		echo -e $"Complete! \nYou now have a new Virtual Host \nYour new host is: http://$map \nAnd its located at $to"
		exit;
	else
		### check whether domain already exists
		if ! [ -e $sitesAvailabledomain ]; then
			echo -e $"This domain does not exist.\nPlease try another one"
			exit;
		else
			### Delete domain in /etc/hosts
			newhost=${domain//./\\.}
			sed -i "/$newhost/d" /etc/hosts

			### disable website
			a2dissite $domain

			### restart Apache
			/etc/init.d/apache2 reload

			### Delete virtual host rules files
			rm $sitesAvailabledomain
		fi

		### check if directory exists or not
		if [ -d $rootDir ]; then
			echo -e $"Delete host root directory ? (y/n)"
			read deldir

			if [ "$deldir" == 'y' -o "$deldir" == 'Y' ]; then
				### Delete the directory
				rm -rf $rootDir
				echo -e $"Directory deleted"
			else
				echo -e $"Host directory conserved"
			fi
		else
			echo -e $"Host directory not found. Ignored"
		fi

		### show the finished message
		echo -e $"Complete!\nYou just removed Virtual Host $domain"
		exit 0;
fi
