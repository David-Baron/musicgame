#!/bin/bash

echo -e "\033[47m\033[1;31m\n[WARNING] This script will update composer and install Musicgame dependencies\033[0m"
confirm()
{
    read -r -p "${1} [y/n] " response

    case "$response" in
        [yY]) 
            true
            ;;
        *)
            false
            ;;
    esac
}

if confirm "\n\033[01;34m[INFO] Execute ?"; then
    echo -e "\n\033[01;34m[INFO] Self-updating Composer\033[00m\n"
    composer self-update

    echo -e "\n\033[00;32m[SUCCESS] Composer self update done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Downloading vendors\033[00m\n"
    composer install --no-progress --prefer-dist --optimize-autoloader

    echo -e "\n\033[00;32m[SUCCESS] Install dependencies done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Create the local storages\033[00m\n"
    mkdir __storage
    mkdir migrations

    echo -e "\n\033[00;32m[SUCCESS] Local storages created done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Create the database\033[00m\n"
    php bin/console d:d:c

    echo -e "\n\033[00;32m[SUCCESS] Database created done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Create the migration\033[00m\n"
    php bin/console make:migration

    echo -e "\n\033[00;32m[SUCCESS] Migration created done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Migrate the migrations\033[00m\n"
    php bin/console d:m:m

    echo -e "\n\033[00;32m[SUCCESS] Migration migrated done\033[00m\n"

    echo -e "\n\033[01;34m[INFO] Load the fixtures\033[00m\n"
    php bin/console doctrine:fixtures:load --group InstallFixtures

    echo -e "\n\033[00;32m[SUCCESS] Fixtures done\033[00m\n"
fi