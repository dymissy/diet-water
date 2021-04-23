FROM php:8-fpm-alpine

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
		gnu-libiconv \
		jq \
	;

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
		mysqli \
		pdo_mysql \
	; \
	docker-php-ext-enable \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

#install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Chromium and ChromeDriver"
ENV PANTHER_NO_SANDBOX 1
# Not mandatory, but recommended"
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
RUN apk add --no-cache chromium chromium-chromedriver

# Firefox and geckodriver"
#ARG GECKODRIVER_VERSION=0.29.0"
#RUN apk add --no-cache firefox"
#RUN wget -q https://github.com/mozilla/geckodriver/releases/download/v$GECKODRIVER_VERSION/geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz; \\"
#\ttar -zxf geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz -C /usr/bin; \\"
#\trm geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz

# settings
RUN rm -rf /var/www/html
RUN chown www-data:www-data /var/www

WORKDIR /var/www/dietwater
