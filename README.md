## Installation

### Laravel Sail
[Documentation](https://laravel.com/docs/8.x/sail)

Copy to bash `.profile`
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

Run sail docker container
```bash
HOME:~/simple_wallet$ sail up
```

### Migration Database
Open in New Terminal
```bash
HOME:~/simple_wallet$ sail php artisan migrate
```

### Postman
Base API url `localhost/api/*`

## Developed By
f4hem
