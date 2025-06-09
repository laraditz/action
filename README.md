# Laravel Action

[![Latest Stable Version](https://poser.pugx.org/laraditz/action/v/stable?format=flat-square)](https://packagist.org/packages/laraditz/action)
[![Total Downloads](https://img.shields.io/packagist/dt/laraditz/action?style=flat-square)](https://packagist.org/packages/laraditz/action)
[![License](https://poser.pugx.org/laraditz/action/license?format=flat-square)](https://packagist.org/packages/laraditz/action)
[![StyleCI](https://github.styleci.io/repos/7548986/shield?style=square)](https://github.com/laraditz/action)

Single action class for Laravel and Lumen to keep your application DRY.

## Installation

Via Composer

```bash
$ composer require laraditz/action
```

## Usage

You can use `php artisan make:action <name>` to create your action. For example, `php artisan make:action CreateNewPost`. By default you can find it in `App/Actions` folder.

Sample action file generated with some logic added as below:

```php
namespace App\Actions;

use App\Models\Post;
use Laraditz\Action\Action;

class CreateNewPost extends Action
{
    public function __construct(
        public string $title,
        public string $body
    )
    {}

    public function handle(): void
    {
        // You can use $this->data() helper to retreive all properties.
        Post::create($this->data());
    }
}
```

Now that you've created your action, you can call it in few ways as below:

**Using plain object**

```php
$createNewPost = new CreateNewPost(
    title: 'My first post',
    body: 'This is a post content'
);

$createNewPost->handle();
```

**Using static method**

```php
CreateNewPost::run(
    title: 'My first post',
    body: 'This is a post content'
);
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email raditzfarhan@gmail.com instead of using the issue tracker.

## Credits

- [Raditz Farhan](https://github.com/raditzfarhan)

## License

MIT. Please see the [license file](LICENSE) for more information.
