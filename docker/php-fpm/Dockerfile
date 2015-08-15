# The container includes:
#
# - Git
# - Nginx
# - PHP Version 5.6.3 (With FPM)
# - Composer
# - Node
# - MySQL Client
# - PostgreSQL Client
# - MongoDB
# - SQLite3
#

FROM azukiapp/node
MAINTAINER Azuki <support@azukiapp.com>

# Add PHP repository to apt source
RUN echo "deb http://ppa.launchpad.net/ondrej/php5-5.6/ubuntu trusty main" \
        > /etc/apt/sources.list.d/php5-5.6.list && \
    apt-key adv --keyserver keyserver.ubuntu.com --recv-key E5267A6C

# Install php5 + nginx + DB clients
RUN apt-get update \
  && apt-get install -y -qq \
      nginx \
      unzip \
      subversion \
      libxml2 \
      libcurl4-openssl-dev \
      php5-dev \
      php5-cli \
      php5-sqlite \
      php5-mysql \
      php5-pgsql \
      php5-mcrypt \
      php5-curl \
      php5-json \
      php5-gd \
      php5-fpm \
      php5-xsl \
      php-pear \
      php-apc \
      sqlite3 libsqlite3-dev \
  && pecl install mongo \
  && apt-get clean -qq \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Enable php modules
RUN echo "extension=mongo.so" > /etc/php5/mods-available/mongo.ini
RUN php5enmod mcrypt mongo

# nginx config
ADD nginx-default.conf /etc/nginx/sites-available/default
RUN  echo "daemon off;" >> /etc/nginx/nginx.conf \
  && echo "fix ownership of sock file for php-fpm as our version of nginx runs as root" \
  && sed -i -e "s/user www-data/user root/g" /etc/nginx/nginx.conf \
  && sed -i -e "s/www-data/root/g" /etc/php5/fpm/pool.d/www.conf \
  && sed -i -e "s/;clear_env = no/clear_env = no/g" /etc/php5/fpm/pool.d/www.conf \
  && sed -i -e "s/DAEMON_ARGS=\"/DAEMON_ARGS=\"--allow-to-run-as-root /g" /etc/init.d/php5-fpm

# tweak php-fpm config
RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf && \
sed -i -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_children = 5/pm.max_children = 9/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.start_servers = 2/pm.start_servers = 3/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 2/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 4/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_requests = 500/pm.max_requests = 200/g" /etc/php5/fpm/pool.d/www.conf


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add image configuration and scripts
ADD run.sh /run.sh
RUN chmod 755 /*.sh


EXPOSE 80
CMD ["/run.sh"]
