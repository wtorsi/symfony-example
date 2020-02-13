FROM nginx:1.17.2-alpine AS builder

# For latest build deps, see https://github.com/nginxinc/docker-nginx/blob/master/mainline/alpine/Dockerfile
RUN set -xe && \
	apk add --no-cache --virtual .build-deps \
	gcc \
	libc-dev \
	make \
	openssl-dev \
	pcre-dev \
	zlib-dev \
	linux-headers \
	curl \
	gnupg \
	libxslt-dev \
	gd-dev \
	geoip-dev \
	git

# Download sources
RUN BROTLIDIR="/tmp/ngx_brotli" && \
	wget "http://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz" -O nginx.tar.gz && \
	git clone --recursive https://github.com/google/ngx_brotli.git $BROTLIDIR

RUN apk add --no-cache --virtual .nginx-deps \
  openssl


# Reuse same cli arguments as the nginx:alpine image used to build
RUN tar -zxC /tmp -f nginx.tar.gz && \
	BROTLIDIR="/tmp/ngx_brotli" && \
  	cd /tmp/nginx-$NGINX_VERSION && \
  	./configure --with-compat --add-dynamic-module=$BROTLIDIR && \
  	&& \
	mv ./objs/*.so / && \
	openssl dhparam -out /dhparam.pem 2048 && \
	apk del .build-deps && \
	rm -rf /tmp/* /var/cache/apk/*

FROM nginx:1.17.2-alpine
# Extract the dynamic module from the builder image
COPY --from=builder /ngx_http_brotli_filter_module.so /usr/local/nginx/modules/
COPY --from=builder /ngx_http_brotli_static_module.so /usr/local/nginx/modules/
COPY --from=builder /dhparam.pem /etc/nginx/dhparam.pem

COPY nginx.conf /etc/nginx/nginx.conf
COPY nginxconfig.io/*.conf /etc/nginx/nginxconfig.io/
COPY default.conf /etc/nginx/default.conf
COPY ssl/* /etc/nginx/ssl/

EXPOSE 80
EXPOSE 443
STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]