# Tracz by RigvedIT production image.
# Builds PHP dependencies and frontend assets from this repo, then layers the
# result onto the official Leantime runtime image (nginx, php-fpm, start.sh).
ARG BASE_VERSION=3.11.0

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist \
        --ignore-platform-reqs --no-interaction --no-progress
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
RUN composer dump-autoload --no-dev --optimize --ignore-platform-reqs \
 && find vendor -type d -name ".git" -prune -exec rm -rf {} + \
 && rm -rf vendor/aws/aws-sdk-php/build vendor/aws/aws-sdk-php/features \
           vendor/aws/aws-sdk-php/tests vendor/aws/aws-sdk-php/.changes

FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY . .
RUN npx mix --production && node generateBlocklist.mjs \
 && find public/dist/js/ -mindepth 1 ! -name "*compiled*" -exec rm -rf {} + \
 && rm -rf public/dist/images/Screenshots

FROM leantime/leantime:${BASE_VERSION}
USER root
WORKDIR /var/www/html

# Replace the stock application code; storage/userfiles stay as in the base image.
RUN rm -rf app bin bootstrap config public vendor
COPY --chown=www-data:root app ./app
COPY --chown=www-data:root bin ./bin
COPY --chown=www-data:root bootstrap ./bootstrap
COPY --chown=www-data:root config ./config
COPY --chown=www-data:root --from=assets /app/public ./public
COPY --chown=www-data:root --from=vendor /app/vendor ./vendor

RUN rm -rf config/.env app/Plugins/* public/userfiles/* userfiles/* \
 && find app/Domain/ -mindepth 2 -maxdepth 2 -type d -name "Js" -exec rm -rf {} + \
 && mkdir -p app/Plugins public/userfiles userfiles bootstrap/cache \
             storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
 && chown -R www-data:root app/Plugins public/userfiles userfiles bootstrap/cache storage \
 && chmod 775 app/Plugins public/userfiles userfiles bootstrap/cache storage/logs \
              storage/framework/cache storage/framework/sessions storage/framework/views

USER www-data
