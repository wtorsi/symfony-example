FROM mysql:latest
ARG DEBIAN_FRONTEND=noninteractive

RUN apt update -q \
 	&& apt -y --no-install-recommends upgrade;

COPY my.conf /etc/mysql/
COPY conf.d/*.conf /etc/mysql/conf.d/

CMD mysql