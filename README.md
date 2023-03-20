# TrainingBlock
TrainingBlockUSA

#Training block Application need PHP Version >= 7.3

#Install Xampp Server with (PHP Version >= 7.3)

Download here: https://www.apachefriends.org/download.html. Ensure the version is 7.3 or 7.4

Xampp will install the correct php version on your system as well.

Then after installation open the app and turn on mysql and apache servers. For more details see #70

#Install composer

brew install composer

(or check the docs, install application based on your system config.)

https://getcomposer.org/download/

#Command for install laravel Packages

composer install

#Download and install node.js using this link

https://nodejs.org/en/download/

#Database Import

import 'trainingblock.sql' file to your Phpmyadmin (http://localhost/phpmyadmin)

#Update app credentials

change DB name (DB_DATABASE) in ".env" file

#Command for install node modules

npm install

#Command for create unique app key

php artisan key:generate

#Command for clear cache

php artisan optimize:clear

#Command for run App

php artisan serve

## Troubleshoot

#Xampp or php command not found

add to your bash profile or .zshrc

```
export XAMPP_HOME=/Applications/XAMPP
export PATH=${XAMPP_HOME}/bin:${PATH}
export PATH
```

#The /Users/stevenli/Documents/Github/steven4354/TrainingBlock/bootstrap/cache directory must be present and writable.

mkdir that folder

#SQLSTATE[HY000] [2002] No such file or directory (SQL: SET FOREIGN_KEY_CHECKS=1;)

xampp app needs to be turned on

open xampp here:
https://capture.dropbox.com/N3QhSunSLdydoGMN

turn on apache and database
https://capture.dropbox.com/usaVOFPhK5bYD0S6

rerun
`php artisan serve`
