FROM php:fpm-alpine

RUN set -xe && \
	apk add --no-cache --virtual .php-deps \
		icu \
		imagemagick && \
	apk add --no-cache --virtual .build-deps \
		make \
		$PHPIZE_DEPS \
		zlib-dev \
		icu-dev \
		imagemagick-dev \
		imagemagick-libs \
		libzip-dev \
		g++ && \
	docker-php-ext-configure intl && \
	docker-php-ext-install -j$(nproc) intl opcache zip pdo_mysql   && \
	pecl install -f -o igbinary && \
	pecl install -f -o redis && \
	pecl install -f -o apcu && \
	pecl install -f -o imagick && \
	docker-php-ext-enable intl opcache zip igbinary redis apcu imagick pdo_mysql && \
	apk del .build-deps && \
	rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/*


COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm.d/www.conf /usr/clocal/etc/php-fpm.d/www.conf
COPY conf.d/* /usr/local/etc/php/conf.d/

CMD ["php-fpm"]



