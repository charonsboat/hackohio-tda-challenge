#!/bin/bash


mysql_database_name="dev"
mysql_user_name="root"
mysql_user_password="dev"


printf "#### Provisioning Vagrant Box..."

printf "#### Updating Vagrant Box..."
# make sure the box is fully up to date
apt-get update -qq > /dev/null

# uncomment the line below to allow the system to upgrade
# apt-get upgrade -y && apt-get dist-upgrade -y

# suppress prompts
export DEBIAN_FRONTEND=noninteractive


printf "#### Installing Necessary Packages..."
# install required packages
apt-get install -qq git nodejs nodejs-legacy npm


printf "#### Installing MySQL..."
# install MySQL
apt-get install -qq mysql-server

# update root password
mysqladmin -u root password ${mysql_user_password}

# create dev database
mysql -u${mysql_user_name} -p${mysql_user_password} -e "create database ${mysql_database_name};"

# run sql query to build db
mysql -u${mysql_user_name} -p${mysql_user_password} ${mysql_database_name} < /vagrant/www/app/db/schema.sql

# run sql query to insert test data into the db
mysql -u${mysql_user_name} -p${mysql_user_password} ${mysql_database_name} < /vagrant/www/app/db/data.sql


printf "#### Installing Nginx..."
# install nginx
apt-get install -qq nginx

# configure nginx
cp /etc/nginx/sites-available/default /etc/nginx/sites-available/default.backup

tee /etc/nginx/sites-available/default << 'EOF'
server {
        listen 80 default_server;
        listen [::]:80 default_server;

        root /vagrant/www/public;
        index index.php index.html index.htm;

        server_name _;

        # for redirects in Vagrant, we don't want the port changing to the internal guest port
        server_name_in_redirect off;
        port_in_redirect        off;

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        error_page 404 /404.html;
        error_page 500 502 503 504 /50x.html;

        location = /50x.html {
              root /usr/share/nginx/www;
        }

        # pass the PHP scripts to FastCGI server listening on the php-fpm socket
        location ~ \.php$ {
                try_files $uri =404;
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
        }

        sendfile off;
}
EOF

# make nginx run as vagrant user
sed -i "s/user www-data;/user vagrant;/g" /etc/nginx/nginx.conf

# add vagrant user to nginx group
usermod -a -G www-data vagrant

# start nginx
service nginx restart


printf "#### Installing PHP..."
# install PHP
apt-get install -qq php5 php5-fpm php5-mysql php5-mcrypt php5-gd

# enable mcrypt
sudo php5enmod mcrypt

# configure PHP
grep -P -q ";?cgi\.fix_pathinfo" /etc/php5/fpm/php.ini && sed -i "s/;\?cgi\.fix_pathinfo.*/cgi.fix_pathinfo=0/g" /etc/php5/fpm/php.ini || echo "cgi.fix_pathinfo=0" | tee --append /etc/php5/fpm/php.ini > /dev/null

# make php run as vagrant user/group
sed -i "s/user = www-data/user = vagrant/g" /etc/php5/fpm/pool.d/www.conf
sed -i "s/group = www-data/group = vagrant/g" /etc/php5/fpm/pool.d/www.conf

# restart php-fpm
service php5-fpm restart


printf "#### Installing NVM..."
# download package and switch to latest version
git clone --quiet https://github.com/creationix/nvm.git /home/vagrant/.nvm && cd /home/vagrant/.nvm
git checkout --quiet `git describe --abbrev=0 --tags`

# backup .bashrc since we're going to change it
cp /home/vagrant/.bashrc /home/vagrant/.bashrc.backup

# automatically source nvm from the .bashrc file on login
echo "source ~/.nvm/nvm.sh" >> /home/vagrant/.bashrc

# set the source of nvm for this session
source /home/vagrant/.nvm/nvm.sh

# install stable version of node with nvm, and set it to the default version
nvm install v0.*
nvm alias default v0.*

# start using this version of Node
nvm use v0.*


printf "#### Installing Sass (CSS Preprocessor)..."
# install Sass gem
gem install sass


printf "#### Installing Composer..."
# install composer package manager
curl -sS https://getcomposer.org/installer | php5
mv composer.phar /usr/local/bin/composer

# make sure composer is up to date
composer selfupdate


# make sure everything in the vagrant directory is owned by the vagrant user
chown -R vagrant:vagrant /vagrant --quiet


# move into the project directory
cd /vagrant/www

# check for environment file. if missing, create it.
if [ ! -f "./.env" ]; then
    printf "#### Environment File Missing. Creating Now..."
    cp .env.example .env
fi

printf "#### Running Composer Install..."
composer install

printf "#### Running NPM Install..."
npm install
