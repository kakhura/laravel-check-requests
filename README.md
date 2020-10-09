## kakhura/laravel-site-bases

### Docs
* [Installation](#installation)
* [Configuration (Config based management)](#configuration)
* [Views](#views)
* [Migrations](#migrations)

## Installation
Add the package in your composer.json by executing the command.

```bash
composer require kakhura/laravel-site-bases
```

For Laravel versions before 5.5 or if not using **auto-discovery**, register the service provider in `config/app.php`

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    \Kakhura\CheckRequest\CheckRequestServiceProvider::class,
],
```


## Configuration

If you want to change ***default configuration***, you must publish default configuration file to your project by running this command in console:
```bash
php artisan vendor:publish --tag=kakhura-site-bases-config
```

This command will copy file `[/vendor/kakhura/laravel-site-bases/config/kakhura.site-basbes.php]` to `[/config/kakhura.site-basbes.php]`

Default `kakhura.site-basbes.php` looks like:
```php
return [
    /**
     * Which methods supports this package.
     */
    'request_methods' => [
        'post',
        'put',
    ],
];
```
## Views
After publish [Configuration](#configuration), you must publish **views**, by running this command in console:
```bash
php artisan vendor:publish --tag=kakhura-site-bases-views
```

This command will copy file `[/vendor/kakhura/laravel-site-bases/resources/views]` to `[/resources/views/vendor/admin/site-bases]`

## Migrations
After publish [Views](#views), you must publish **migrations**, by running this command in console:
```bash
php artisan vendor:publish --tag=kakhura-site-bases-migrations
```

This command will copy file `[/vendor/kakhura/laravel-site-bases/database/migrations]` to `[/database/migrations]`

After publish [Migrations](#migrations), you must run this command in console:
```bash
php artisan kakhura:run-commands
```
This command creates some necessary stuffs.
