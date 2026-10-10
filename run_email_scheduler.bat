@echo off
title Laravel Email Sync Scheduler
cd /d "%~dp0"
echo =======================================================
echo    Laravel Real-Time Email Background Scheduler
echo =======================================================
echo Checking and syncing incoming emails every minute...
php artisan schedule:work
