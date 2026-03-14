FROM php:7.4-apache

# Install PHP extensions and utilities
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set PHP configuration
RUN echo "memory_limit = 2048M" > /usr/local/etc/php/conf.d/swete.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/swete.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/swete.ini

# OPcache configuration for Cloud Run (faster cold starts)
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configure Apache to listen on PORT env var (Cloud Run requirement)
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf
ENV PORT=8080

WORKDIR /var/www/html

# Copy application source
COPY . .

# Create writable directories
RUN mkdir -p swete-admin/templates_c swete-admin/livecache swete-admin/snapshots \
    && chown -R www-data:www-data swete-admin/templates_c swete-admin/livecache swete-admin/snapshots

# Download dependencies (Xataface, SweteApp, modules)
RUN cd bin && bash setup.sh

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
