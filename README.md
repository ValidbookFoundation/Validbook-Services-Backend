RESTful API for Validbook
==================================================

Validbook backend with RESTful API configured. Take a look at http://api.validbook.org for more detail explanation

## Install Composer Packages
You need [Composer](http://getcomposer.org) installed first.
```
composer self-update
```
```
composer install
```

## Create Database
create your database

## Environment File
Open terminal and go to the project folder and edit environment file .env

Linux
```
nano .env
```

## Run Database Migration
This command will create all tables for the project

```
./yii migrate
```

## Enable Mod Rewrite if you use Apache
Make sure you already enable this mod. Follow this [Tutorial](http://stackoverflow.com/questions/869092/how-to-enable-mod-rewrite-for-apache-2-2)