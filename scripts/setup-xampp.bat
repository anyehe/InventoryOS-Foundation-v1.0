@echo off
setlocal
cd /d %~dp0..\
echo Inventory Platform - XAMPP setup
where php >nul 2>&1 || (echo PHP not found. Add C:\xampp\php to PATH. & exit /b 1)
where composer >nul 2>&1 || (echo Composer not found. Install Composer and retry. & exit /b 1)
if not exist .env copy .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan optimize:clear
echo.
echo Setup complete. Create database 'inventory_platform' in phpMyAdmin if it does not exist.
pause
