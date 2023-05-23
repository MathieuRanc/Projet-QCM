FROM php:8-apache

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    python3 \
    python3-pip \
    libmagickwand-dev \
    imagemagick \
    libcurl4-openssl-dev \
    curl \
    unzip \
    --no-install-recommends \
    && docker-php-ext-enable opcache \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql -j$(nproc) gd curl \
    && apt-get autoclean -y \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Update ImageMagick policy
RUN sed -i '/PDF/s/none/read|write/' /etc/ImageMagick-6/policy.xml

# Update apache conf to point to application public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Update uploads config
RUN echo "file_uploads = On\n" \
    "memory_limit = 1024M\n" \
    "upload_max_filesize = 512M\n" \
    "post_max_size = 512M\n" \
    "max_execution_time = 1200\n" \
    > /usr/local/etc/php/conf.d/uploads.ini

# Enable headers module
RUN a2enmod rewrite headers

# Copy the requirements file into the Docker container
COPY bin/requirements.txt /tmp/requirements.txt

# Install the Python dependencies
RUN pip3 install -r /tmp/requirements.txt

# Set the working directory in the container
WORKDIR /var/www

# Copy the application files to the Docker container
COPY . .

# Install the PHP dependencies
RUN composer install

# Set permission on storage folder
RUN chmod -R 755 storage
RUN chmod 755 ./.env
# RUN php artisan key:generate
