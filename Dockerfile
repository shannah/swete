FROM php:7.4-apache

# Install PHP extensions and utilities
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set PHP configuration
RUN echo "memory_limit = 2048M" > /usr/local/etc/php/conf.d/swete.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/swete.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/swete.ini

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

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

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
