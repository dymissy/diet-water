FROM php:8-fpm-alpine

#RUN mkdir -p /usr/share/man/man1
#RUN apt-get update && apt-get install -y \
#    curl \
#    vim \
#    git \
#    software-properties-common \
#    bsd-mailx \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#    libmcrypt-dev \
#    libpng-dev \
#    libxml2-dev \
#    libzip-dev \
#    unzip

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

#RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd bcmath soap intl pdo pdo_mysql zip

#install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# settings
RUN rm -rf /var/www/html
RUN chown www-data:www-data /var/www

WORKDIR /var/www/dietwater
