#!/bin/bash

#Follow https://medium.com/@runawaycoin/deploying-symfony-4-application-to-shared-hosting-with-just-ftp-access-e65d2c5e0e3d
#Variables
echo -e "\e[1;42m Exporting Variables REPO_DIR, PUBLIC_DIR, SYMFONY_DIR \e[0m" 
export REPO_DIR=/home/sjmh5evypg7s/repository
export PUBLIC_DIR=/home/sjmh5evypg7s/public_html
export SYMFONY_DIR=/home/sjmh5evypg7s/symfony
#/Variables

#ensure webpack encore is installed
echo -e "\e[1;42m composer require symfony/webpack-encore-bundle \e[0m" 
composer require symfony/webpack-encore-bundle
echo -e "\e[1;42m yarn install \e[0m" 
yarn install
#/ensure webpack encore is installed

#Composer build vendor stuff
echo -e "\e[1;42m (cd $REPO_DIR && composer install --no-dev --optimize-autoloader) \e[0m" 
(cd $REPO_DIR && composer install --no-dev --optimize-autoloader)
echo -e "\e[1;42m (cd $REPO_DIR && composer dump-autoload --optimize --no-dev --classmap-authoritative) \e[0m" 
(cd $REPO_DIR && composer dump-autoload --optimize --no-dev --classmap-authoritative)
#/Composer build vendor stuff
#yarn build vendor stuff
echo -e "\e[1;42m yarn encore production --progress (public/build) \e[0m" 
yarn encore production --progress
#/yarn build vendor stuff

#migrate db
echo -e "\e[1;42m Enabling migrations \e[0m" 
php bin/console doctrine:migrations:migrate --no-interaction
#/migrate db

#Reset symfony directory
echo -e "\e[1;42m Replacing symfony directory \e[0m"
/bin/rm -r $SYMFONY_DIR
/bin/mkdir -p $SYMFONY_DIR
#/Reset symfony directory

#Change line 7 of index.php in the public folder
echo -e "\e[1;42m Replacing line 7 in index.php \e[0m"
/bin/sed -i "s#dirname(__DIR__).'/config/bootstrap.php'#__DIR__.'/../symfony/config/bootstrap.php'#g" $REPO_DIR/public/index.php
echo -e "\e[1;42m Replacing line 3 in webpack_encore.yaml \e[0m"
/bin/sed -i "s#output_path: '%kernel.project_dir%/public/build'#output_path: '%kernel.project_dir%/../public_html/build'#g" $REPO_DIR/config/packages/webpack_encore.yaml
echo -e "\e[1;42m Replacing line 3 in assets.yaml \e[0m"
/bin/sed -i "s#json_manifest_path: '%kernel.project_dir%/public/build/manifest.json'#json_manifest_path: '%kernel.project_dir%/../public_html/build/manifest.json'#g" $REPO_DIR/config/packages/assets.yaml
#/Change line 7 of index.php in the public folder
#Copy to public folder
echo -e "\e[1;42m Emptying contents of public_html in preperation \e[0m"
/bin/rm -rf $PUBLIC_DIR/*
echo -e "\e[1;42m Copying contents of public to public_html \e[0m"
/bin/cp -a $REPO_DIR/public/. $PUBLIC_DIR
#/Copy to public folder
#Copy console tools
echo -e "\e[1;42m Copying console tools \e[0m"
/bin/cp -a $REPO_DIR/bin $SYMFONY_DIR
 #/Copy console tools
 #Copy symfony src thats needed for deployment
 echo -e "\e[1;42m Copying config \e[0m"
/bin/cp -a $REPO_DIR/config $SYMFONY_DIR
 echo -e "\e[1;42m Copying src \e[0m"
/bin/cp -a $REPO_DIR/src $SYMFONY_DIR
 echo -e "\e[1;42m Copying templates \e[0m"
/bin/cp -a $REPO_DIR/templates $SYMFONY_DIR
 echo -e "\e[1;42m Copying translations \e[0m"
/bin/cp -a $REPO_DIR/translations $SYMFONY_DIR
 echo -e "\e[1;42m Copying vendor \e[0m"
/bin/cp -a $REPO_DIR/vendor $SYMFONY_DIR
#/Copy symfony src thats needed for deployment
#Create the "var" folder if it doesnt already exist
 echo -e "\e[1;42m Making var directory \e[0m"
/bin/mkdir -p $SYMFONY_DIR/"var"
#/Create the "var" folder if it doesnt already exist
#Copy composer json, lock and env files
 echo -e "\e[1;42m Copying composer, lock and env files \e[0m"
/bin/cp $REPO_DIR/composer.json $SYMFONY_DIR
/bin/cp $REPO_DIR/composer.lock $SYMFONY_DIR
/bin/cp $REPO_DIR/.env $SYMFONY_DIR
#/Copy composer json and lock files
 echo -e "\e[1;42m DONE! \e[0m"