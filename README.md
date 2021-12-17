### Installation
- `git clone https://github.com/fahmiardi/simple_wallet.git`
- `cd simple_wallet`
- `cp .env.example .env`
- `composer install`
- `php artisan key:generate --ansi`
- edit file `.env`
  * DB_HOST=mysql
  * DB_DATABASE=wallets
  * DB_USERNAME=sail
  * DB_PASSWORD=password

### Environment
- add command `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'` to file `~/.profile`
- reload bash `source ~/.profile`

### Start Docker Container
`sail up -d`

### Migrate database
`sail php artisan migrate`

### Stop Docker Container
`sail stop`

### Remote database on container
- host: 127.0.0.1
- port: 3306
- user: sail
- password: password
- database: wallets

### Base api url
`localhost/api/*`

### Developed by
[f4hem](mailto:f4hem.net@gmail.com)