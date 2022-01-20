echo off

echo [WARN] This script will update composer and install Musicgame dependencies.

echo [INFO] Self-updating Composer
composer self-update

echo [SUCCESS] Composer self update done

echo [INFO] Downloading vendors
composer install --prefer-dist --optimize-autoloader

echo [SUCCESS] Install dependencies done

echo [INFO] Create the local storage
mk dir __storage

echo [SUCCESS] Local storage created done

echo [INFO] Create the database
php bin/console d:d:c

echo [SUCCESS] Database created done

echo [INFO] Create the migration
php bin/console make:migration

echo [SUCCESS] Migration created done

echo [INFO] Migrate the migrations
php bin/console d:m:m

echo [SUCCESS] Migration migrated done

echo [INFO] Load the fixtures
php bin/console doctrine:fixtures:load --group InstallFixtures

echo [SUCCESS] Fixtures done