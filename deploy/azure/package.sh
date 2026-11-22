#!/bin/sh
set -eu

# Build from tracked source so local credentials and uploads never enter the ZIP.
repo_root=$(git rev-parse --show-toplevel)
archive_path=$(realpath -m "${1:-/tmp/indofx-azure.zip}")
work_dir=$(mktemp -d)
trap 'rm -rf "$work_dir"' EXIT HUP INT TERM

mkdir -p "$work_dir/source"
cd "$repo_root"
git archive HEAD app routes resources public deploy composer.json composer.lock | tar -x -C "$work_dir/source"
rm -rf "$work_dir/source/public/uploads" "$work_dir/source/public/assets/uploads"
mkdir -p "$work_dir/source/storage"
composer install --working-dir="$work_dir/source" --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress
(cd "$work_dir/source" && zip -q -r "$work_dir/package.zip" .)
mv "$work_dir/package.zip" "$archive_path"
printf 'Deployment archive: %s\n' "$archive_path"
