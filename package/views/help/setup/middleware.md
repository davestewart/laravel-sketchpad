To assign middleware to Sketchpad, create a custom service provider file at:

```
app/Providers/SketchpadServiceProvider.php
```

The class should extend Sketchpad's own service provider, and needs only a single `middleware` property:

```php
namespace app\Providers;

class SketchpadServiceProvider extends \davestewart\sketchpad\SketchpadServiceProvider
{
    protected $middleware = ['auth'];
}
```

To ensure the new provider is loaded, update your `app.php` config:

```
// 3rd-party Service Providers...
App\Providers\SketchpadServiceProvider::class
```

To assign middleware per controller, just use [Controller Middleware](https://laravel.com/docs/5.0/controllers#controller-middleware).