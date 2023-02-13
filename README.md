PREPARATION FOR FIRST TIME
-	clone application from github
-	cd migisapps-v1
-	sudo chown ${USER}:www-data -R backend

A.	Configuration Backend
1)	cd backend
2)	composer install
3)	cp .envold to .env
a)	Base config database
i)	change DB_DATABASE='name_database'
ii)	change DB_USERNAME='your_db_username'
iii)	change DB_PASSWORD='your_db_password'
iv)	change DB_HOST=127.0.0.1 // optional
v)	change DB_PORT=3306 // optional
b)	Config database for sync absensi, 
i)	DB_HOST_ABSENSI=127.0.0.1 // optional, base on your server IP Database
ii)	DB_PORT_ABSENSI=3306 // optional, base on your SERVER PORT
iii)	DB_DATABASE_ABSENSI=db_absensi // change if name different
iv)	DB_USERNAME_ABSENSI='your_db_username'
v)	DB_PASSWORD_ABSENSI='your_db_password'
4)	php artisan cache:clear
5)	php artisan migrate
6)	php artisan db:seed
7)	php artisan key:generate
8)	Permissions
a)	sudo chown ${USER}:www-data -R storage/app
b)	sudo chown ${USER}:www-data -R storage/framework
c)	sudo chown ${USER}:www-data -R storage/logs
d)	sudo chmod 777 -R storage/app
e)	sudo chmod 777 -R storage/framework
f)	sudo chmod 777 -R storage/logs
9)	uncomment cron script in app/Console/Kernel.php

B.	Configuration Cron Job & Laravel-Worker
1)	Setting Laravel task/cronjob
a)	cd backend
b)	crontab -e
c)	add “ * * * * * _to_path_/backend/artisan schedule:run >> /dev/null 2>&1 “
d)	close and save.
e)	Sudo /etc/init.d/cron reload
2)	Setting Laravel Worker
a)	make sure supervisor installed, enable, and active
b)	sudo nano /etc/supervisor/conf.d/_name_file_.conf
c)	fill with this:
[program:_program_name_]
process_name=%(program_name)s_%(process_num)02d
command=php _path_to_/backend/artisan queue:work --tries=3
autostart=true
autorestart=true
user=_user_name_
numprocs=2 
redirect_stderr=true
stdout_logfile=_path_to_/backend/storage/_program_name.log
d)	exit save
e)	sudo supervisorctl reread
f)	sudo supervisorctl update

C.	Configuration Frontend
1)	cd frontend
2)	npm run install
a)	For Production
i)	nano .env.prod
ii)	change VUE_APP_API_ENDPOINT='your_api_address'
iii)	npm run build --mode production
iv)	chmod -R dist/
b)	For Staging /Testing
i)	nano .env
ii)	change VUE_APP_API_ENDPOINT='your_api'
iii)	(npm run serve&) or (npm run serve -- --port='your_port')

D.	Make sure all Port used already open on Firewall
