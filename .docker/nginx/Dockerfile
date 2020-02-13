FROM nginx:latest
ARG DEBIAN_FRONTEND=noninteractive

RUN apt update -q \
	&& apt -y --no-install-recommends upgrade;

COPY nginx.conf /etc/nginx/
COPY conf.d/*.conf /etc/nginx/conf.d/
COPY sites-available/*.conf /etc/nginx/sites-available/

RUN mkdir -p /etc/nginx/sites-enabled \
	&& ln -s /etc/nginx/sites-available/*.conf /etc/nginx/sites-enabled/

CMD ["nginx"]
EXPOSE 80
EXPOSE 443