#!/bin/sh
set -eu

app_root=/home/site/wwwroot
data_root=/home/indofx

# Runtime data stays outside the directory replaced by deployments.
for relative in public/uploads storage/uploads storage/logs storage/cache storage/invoices; do
    source_dir="$app_root/$relative"
    target_dir="$data_root/$relative"
    mkdir -p "$(dirname "$target_dir")" "$(dirname "$source_dir")"
    if [ -L "$source_dir" ]; then
        if [ "$(readlink "$source_dir")" != "$target_dir" ]; then
            echo "Unexpected storage link: $source_dir" >&2
            exit 1
        fi
    elif [ -d "$source_dir" ]; then
        if [ -d "$target_dir" ]; then
            cp -an "$source_dir/." "$target_dir/"
            backup_dir="$data_root/deployment-backups/$(date +%Y%m%d%H%M%S)/$(dirname "$relative")"
            mkdir -p "$backup_dir"
            mv "$source_dir" "$backup_dir/$(basename "$relative")"
        else
            mv "$source_dir" "$target_dir"
        fi
    fi
    mkdir -p "$target_dir"
    if [ ! -L "$source_dir" ]; then
        ln -s "$target_dir" "$source_dir"
    fi
done

mkdir -p "$data_root/sessions"
chown -R www-data:www-data "$data_root"
php -r 'foreach (["pdo_pgsql", "mbstring", "curl", "openssl"] as $ext) { if (!extension_loaded($ext)) { fwrite(STDERR, "Missing PHP extension: $ext\n"); exit(1); } }'
cp "$app_root/deploy/azure/nginx.conf" /etc/nginx/sites-available/default
nginx -t
service nginx reload
