init:
	composer update
	cp .env.example .env
	php artisan key:generate
	npm install
	npm run build
	touch database/database.sqlite
	php artisan migrate
	
run:
	php artisan serve


test:
	php artisan test

