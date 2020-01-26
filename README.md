# Test for a company in the igaming industry.

## Target

Aim of the test was to create and MVC solution in PHP to:

1. Upload the file containing a number of poker hands two players had played
2. Parse file into a DB
3. Show how many hands player 1 wins
4. Have some basic Authentication system.

## Requirements

* PHP 7.x
* MySQL 5.7.x or greater

## Installation System

Assuming Debian 9 as host. 

```
sudo apt update
sudo apt upgrade
```

```
sudo apt install nginx php-fpm php-mysql mariadb-server mariadb-client git -y
```

### Database MySQL/MariaDB

run the mysql secure installation 

```
sudo mysql_secure_installation
```

connect to the database from the cli

```
mysql -u root -p
```

Create a new database

```
MariaDB [(none)]> CREATE DATABASE newtestdb;
```

Create new user and assign privileges ( eg: 'username' and 'password' with full privileges on newtestdb.* )

```
MariaDB [(none)]> GRANT ALL PRIVILEGES ON newtestdb.* TO 'username'@'localhost' IDENTIFIED BY 'password';
```

```
MariaDB [(none)]> exit;
```

### Clone Repository

```
cd /var/www

git clone https://github.com/blugenus/test-for-igaming-poker-parser.git

cd \var\www\test-for-igaming-poker-parser\config\

sudo cp database-sample.json database.json

sudo nano database.json

```

and fill in the database configuration details we set before:

```
{
    "host": "127.0.0.1",
    "username": "username",
    "password": "password",
    "database": "newtestdb",
    "port": 3306
}
```

### Composer

```
cd ~

curl -sS https://getcomposer.org/installer -o composer-setup.php

HASH="$(wget -q -O - https://composer.github.io/installer.sig)"

php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

cd \var\www\test-for-igaming-poker-parser\

sudo composer install

```

### Nginx

```
cd /etc/nginx/sites-available/

sudo rm default

sudo nano test-for-igaming-poker-parser

```

Paste the following configuration

```
server {
    listen       80;

    root /var/www/test-for-igaming-poker-parser/public/;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP location:
    location ~ \.php$ {

        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME     $document_root$fastcgi_script_name;

    }

}
```

```
cd /etc/nginx/sites-enabled/

sudo rm default

sudo ln -s /etc/nginx/sites-available/test-for-igaming-poker-parser /etc/nginx/sites-enabled/

sudo systemctl reload nginx

```

### Php-fpm

Should work out of the box :)

## System Setup

Launch your favorite browser and open the ip where you installed the system followed by /setup.php

example: http://127.0.0.1/setup.php

you should be received by a message static:

"We are done with setting it up :)"

click on the link to be redirected to the login page.

## Using the System

The username and password are 'admin' and '123' respectively.

Once you are logged in simply click the 'Choose File' button, choose the rank file and click the 'Upload' button.

From the same 1000 hands provided:

Player 1 won 376

Player 2 won 624

