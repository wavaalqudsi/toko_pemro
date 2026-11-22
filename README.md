Anjay AI

git clone https://github.com/wavaalqudsi/toko_pemro.git
cd toko_pemro
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
