FROM php:fpm
ARG DEBIAN_FRONTEND=noninteractive

RUN apt update -q \
	&& apt -y --no-install-recommends upgrade \
	&& apt -y --no-install-recommends install git curl locales \
	&& apt-get -y --no-install-recommends install libicu-dev g++ zlib1g-dev \
    && docker-php-ext-install -j$(nproc) opcache intl zip \
    && apt-get -y --no-install-recommends install libmagickwand-dev \
	&& pecl install igbinary \
	&& pecl install redis \
	&& pecl install imagick \
	&& docker-php-ext-enable igbinary redis apcu \
	&& curl -sSk https://getcomposer.org/installer | php -- --disable-tls \
	&& mv composer.phar /usr/local/bin/composer;


COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY conf.d/* /usr/local/etc/php/conf.d/

RUN rm -rf /var/lib/apt/lists/* \
 	&& mkdir -p /var/log/php;

CMD ["php-fpm"]



