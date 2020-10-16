## kakhura/laravel-check-requests

### Docs
* [Installation](#installation)
* [Configuration (Config based management)](#configuration)
* [Views](#views)
* [Migrations](#migrations)

## Installation
Add the package in your composer.json by executing the command.

```bash
composer require kakhura/laravel-check-requests
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
php artisan vendor:publish --tag=kakhura-check-requests-config
```

This command will copy file `[/vendor/kakhura/laravel-check-requests/config/kakhura.check-requests.php]` to `[/config/kakhura.check-requests.php]`

Default `kakhura.check-requests.php` looks like:
```php
return [
    /**
     * Which methods supports this package.
     */
    'request_methods' => [
        'post',
        'put',
    ],

    /**
     * Package use or not auth user check.
     */
    'use_auth_user_check' => false,
];
```
## Views
After publish [Configuration](#configuration), you must publish **views**, by running this command in console:
```bash
php artisan vendor:publish --tag=kakhura-check-requests-views
```

This command will copy file `[/vendor/kakhura/laravel-check-requests/resources/views]` to `[/resources/views/vendor/admin/check-requests]`

## Migrations
After publish [Views](#views), you must publish **migrations**, by running this command in console:
```bash
php artisan vendor:publish --tag=kakhura-check-requests-migrations
```

This command will copy file `[/vendor/kakhura/laravel-check-requests/database/migrations]` to `[/database/migrations]`

After publish [Migrations](#migrations), you must add `HasRelatedRequest` trait in your model in which you want check if request already had sent:
```php
use Kakhura\CheckRequest\Traits\Models\HasRelatedRequest;

class Application extends Model
{
    use HasRelatedRequest;
}

```
You must create `RequestIdentifier` instance in all your model create functionality like this:
```php
use Models\Application;

class ApplicationService extends Service
{
    public fucntion create(array $data) 
    {
        ...
        $application = Application::create($data);
        $application->createRequestIdentifier(strval($requestId));
        ...
    }
}

```
After this, all of your route on which you want to check request existence, you must use middleware alias `with_request_identifier`.

Also, you can check if request already sent and receive model on which request had sent. Endpoint is `http://domain.com/requests/check/{requestId}`. This endpoint return 404 not found if request not found. If request found, you will receive response like this:

 ```php
return [
    'model' => 'application',
    'id' => 'model_uuid' ?: 'model_id',
];
 ```

Enjoy.
