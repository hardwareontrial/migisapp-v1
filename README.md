PREPARATION

- clone application from github
- cd migisapps-v1
- chown ${USER}:www-data -R backend

Configuration Backend
- cd backend
- composer install
- cp .envold to .env
  /** Base config database */
  <!--- Base config database -->
    + change DB_DATABASE='name_database'
    + change DB_USERNAME='your_db_username'
    + change DB_PASSWORD='your_db_password'
    + change DB_HOST=127.0.0.1 // optional
    + change DB_PORT=3306 // optional
  /** Config database for sync absensi, 
    * db_absensi (or whatever db name) must be on database first 
    * this db from fingerprint fingerspot
    */
    + DB_HOST_ABSENSI=127.0.0.1 // optional, base on your server IP Database
    + DB_PORT_ABSENSI=3306 // optional, base on your SERVER PORT
    + DB_DATABASE_ABSENSI=db_absensi // change if name different
    + DB_USERNAME_ABSENSI='your_db_username'
    + DB_PASSWORD_ABSENSI='your_db_password'
- php artisan cache:clear
- php artisan migrate
- php artisan db:seed
- php artisan key:generate
- chown ${USER}:www-data -R storage/app
- chown ${USER}:www-data -R storage/framework
- chown ${USER}:www-data -R storage/logs
- chmod 777 -R storage/app
- chmod 777 -R storage/framework
- chmod 777 -R storage/logs
- uncomment cron script in app/Console/Kernel.php

Configuration Frontend
- cd frontend
- npm run install
  /** For production */
  + nano .env.prod
  + change VUE_APP_API_ENDPOINT='your_api_address'
  + npm run build --mode production
  + chmod -R dist/
  /** For staging or testing */
  + nano .env
  + change VUE_APP_API_ENDPOINT='your_api'
  + (npm run serve&) or (npm run serve -- --port='your_port') // if your api using specific port

Configuration Cron Job & Laravel-Worker

- if your server working with Firewall, make sure all port used is allowed.
