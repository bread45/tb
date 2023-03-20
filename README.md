# TrainingBlock
TrainingBlockUSA

#Training block Application need PHP Version >= 7.3

#Install Xampp Server with (PHP Version >= 7.3)

Download here: https://www.apachefriends.org/download.html. Ensure the version is 7.3 or 7.4

Xampp will install the correct php version on your system as well.

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

