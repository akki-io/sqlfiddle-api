FROM akkica/laravel-web:7.4

# add install-php-extension - https://github.com/mlocati/docker-php-extension-installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync

# install pdo pgsql
RUN install-php-extensions pdo_pgsql

# install pdo for mssql
RUN install-php-extensions pdo_sqlsrv
