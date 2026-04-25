FROM dunglas/frankenphp:1-php8.4-alpine

# 1. Install Tooling, MariaDB, Redis, supervisor, Node, & Chromium
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    mariadb \
    mariadb-client \
    redis \
    supervisor \
    nodejs \
    npm \
    chromium \
    nss \
    freetype \
    harfbuzz \
    ca-certificates \
    ttf-freefont

# 2. Install PHP Extensions
RUN install-php-extensions pdo_mysql gd intl zip opcache pcntl

# 3. Environment Variables for Puppeteer
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

# 4. Setup direktori kerja & izin akses untuk MySQL/Redis/App (PENTING: Gunakan /run/mysqld untuk Alpine)
RUN mkdir -p /run/mysqld /var/lib/mysql /var/log/redis /var/log/supervisor && \
    chown -R mysql:mysql /var/lib/mysql /run/mysqld && \
    chown -R www-data:www-data /app && \
    chown -R mysql:mysql /var/log/mysql 2>/dev/null || true

WORKDIR /app

# 5. Salin konfigurasi Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 6. Salin entrypoint milik Anda
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 7. Setup Composer & Copy Dependensi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock package.json ./

# Install PHP & Node dependencies
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist && \
    npm install

# 7. SALIN SELURUH SOURCE CODE APLIKASI (Termasuk file artisan)
COPY . .

# 8. Baru jalankan dump-autoload dan script post-install Laravel
RUN composer dump-autoload --optimize

# 9. Perbaiki izin akses untuk file yang baru disalin
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000 3306 6379

ENTRYPOINT ["docker-entrypoint.sh"]
