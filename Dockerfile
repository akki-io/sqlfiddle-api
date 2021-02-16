FROM akkica/laravel-web:7.4

RUN apk update

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install \
    pdo_pgsql
