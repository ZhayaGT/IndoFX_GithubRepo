#!/bin/sh
set -eu

app_root=/data/indofx
release=${1:?Usage: sudo sh deploy/instabox/install.sh /data/indofx/releases/COMMIT}
case "$release" in "$app_root"/releases/*) ;; *) echo 'Invalid release path' >&2; exit 1;; esac
[ -f "$release/public/index.php" ]
[ -f "$app_root/shared/.env" ]

mkdir -p "$app_root/shared/sessions" "$app_root/shared/logs"
for relative in public/uploads storage/uploads storage/logs storage/cache storage/invoices; do
    mkdir -p "$app_root/shared/$relative" "$(dirname "$release/$relative")"
    if [ ! -L "$release/$relative" ]; then
        [ ! -e "$release/$relative" ] || { echo "Unexpected runtime directory: $relative" >&2; exit 1; }
        ln -s "$app_root/shared/$relative" "$release/$relative"
    fi
done
[ -L "$release/.env" ] || ln -s "$app_root/shared/.env" "$release/.env"
chown -R www-data:www-data "$app_root/shared"
chmod 640 "$app_root/shared/.env"
chmod 700 "$app_root/shared/sessions"

# Keep the previous release available for rollback; switch the active path atomically.
if [ -L "$app_root/current" ]; then
    ln -sfn "$(readlink "$app_root/current")" "$app_root/previous"
fi
ln -sfn "$release" "$app_root/current.next"
mv -Tf "$app_root/current.next" "$app_root/current"

install -m 644 "$release/deploy/instabox/php.ini" /etc/php/8.4/fpm/conf.d/99-indofx.ini
install -m 644 "$release/deploy/instabox/nginx.conf" /etc/nginx/sites-available/indofx
ln -sfn /etc/nginx/sites-available/indofx /etc/nginx/sites-enabled/indofx
php-fpm8.4 -t
nginx -t
systemctl enable php8.4-fpm nginx
systemctl restart php8.4-fpm
# The earlier installation used PHP's development server on the same port.
if systemctl is-active --quiet indofx.service; then systemctl stop indofx.service; fi
systemctl disable indofx.service 2>/dev/null || true
systemctl restart nginx
