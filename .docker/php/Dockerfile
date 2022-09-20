FROM php:7.4-apache

ARG USERNAME=appuser
ARG UID

# Add user to execute all as a local user
RUN if [ "${UID}" != "" ] ; \
     then adduser -u $UID --disabled-password --gecos "" $USERNAME ; \
     else adduser --disabled-password --gecos "" $USERNAME ; \
    fi

RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y sudo git zip unzip npm \
    && echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
    && chmod 0440 /etc/sudoers.d/$USERNAME

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

ENV APP_ROOT /var/www
ENV APACHE_DOCUMENT_ROOT $APP_ROOT/public

WORKDIR $APP_ROOT

COPY .docker/apache/000-default_dev.conf /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf