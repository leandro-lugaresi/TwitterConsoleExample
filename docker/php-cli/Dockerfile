FROM azukiapp/node
MAINTAINER Azuki <support@azukiapp.com>

# Add PHP repository to apt source
RUN echo "deb http://ppa.launchpad.net/ondrej/php5-5.6/ubuntu trusty main" \
        > /etc/apt/sources.list.d/php5-5.6.list && \
    apt-key adv --keyserver keyserver.ubuntu.com --recv-key E5267A6C

# Install php5 + DB clients
RUN apt-get update \
  && apt-get install -y -qq \
      unzip \
      subversion \
      libxml2 \
      libcurl4-openssl-dev \
      php5-dev \
      php5-cli \
      php5-fpm \
      php5-sqlite \
      php5-mysql \
      php5-pgsql \
      php5-mcrypt \
      php5-curl \
      php5-json \
      php5-gd \
      php5-xsl \
      php-pear \
      php-apc \
      sqlite3 libsqlite3-dev \
      supervisor \
  && pecl install mongo \
  && apt-get clean -qq \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Enable php modules
RUN echo "extension=mongo.so" > /etc/php5/mods-available/mongo.ini
RUN php5enmod mcrypt mongo

# supervisor config OR /etc/supervisord.conf
ADD supervisor.conf /etc/supervisor/conf.d/supervisor.conf

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD run.sh /run.sh
RUN chmod 755 /*.sh

CMD ["/run.sh"]
