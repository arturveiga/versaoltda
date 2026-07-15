FROM wordpress:php8.2-apache

# Keep the PHP limits inside the image so Coolify does not depend on a bind
# mount from the checked-out repository at runtime.
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-versao-ltda.ini

# Replace the source bundled by the official entrypoint with this repository.
# On every container start, the WordPress entrypoint copies this tree to
# /var/www/html, including the custom theme and bundled plugins.
COPY --chown=www-data:www-data . /usr/src/wordpress

# wp-config.php is ignored by Git. Promote the versionable example into the
# path consumed by WordPress after the repository files are copied.
COPY --chown=www-data:www-data wp-config.example /usr/src/wordpress/wp-config.php
