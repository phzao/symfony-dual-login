FROM php:7.3-fpm
WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install wget iputils-ping git vim openssl zip unzip zlib1g-dev libzip-dev libpq-dev\
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

RUN docker-php-ext-install pdo pgsql pdo_pgsql zip
RUN export PATH="$HOME/.symfony/bin:$PATH"

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer