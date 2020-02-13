FROM composer:1.8.6-alpine

#RUN apt update \
#	&& apt upgrade \
#	&& apt install -y git curl locales zip \
#	&& echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
#	&& locale-gen;

COPY ../* ./
RUN yarn encore production \
 	&& composer install --no-dev -oa --apcu-autoloader
 	&& bin/console doctrine:migrations:migrate -e prod


