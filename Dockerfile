FROM php:7.4.33-fpm

COPY --from=composer:2.5.8 /usr/bin/composer /usr/bin/composer

RUN apt-get update -qq && \
    apt-get upgrade -y && \
    apt-get install -qqy --no-install-recommends \
        libicu-dev \
        libxml2-dev \
        libzip-dev \
        curl \
        git \
        unzip

RUN docker-php-ext-install bcmath intl pdo_mysql

RUN ln -snf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && \
    echo "date.timezone = UTC" >> /usr/local/etc/php/conf.d/datetime.ini;

RUN adduser --disabled-password --gecos '' app

CMD ["php-fpm"]
