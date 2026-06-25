# Laravel Action

[![Latest Stable Version](https://poser.pugx.org/laraditz/action/v/stable?format=flat-square)](https://packagist.org/packages/laraditz/action)
[![Total Downloads](https://img.shields.io/packagist/dt/laraditz/action?style=flat-square)](https://packagist.org/packages/laraditz/action)
[![License](https://poser.pugx.org/laraditz/action/license?format=flat-square)](https://packagist.org/packages/laraditz/action)

Single action class for Laravel to keep your application DRY. Each action encapsulates one business operation, receives its input through the constructor, and resolves services automatically via Laravel's container.

## Requirements

- PHP 8.2+
- Laravel 9 – 13

## Installation

```bash
composer require laraditz/action
```

## Creating an Action

Use the Artisan command to generate an action class (placed in `app/Actions/` by default):

```bash
php artisan make:action CreateNewPost
```

Fill in the generated file:

```php
namespace App\Actions;

use App\Models\Post;
use Laraditz\Action\Action;

class CreateNewPost extends Action
{
    public function __construct(
        public string $title,
        public string $body,
    ) {}

    public function handle(): Post
    {
        return Post::create($this->data());
    }
}
```

## Running an Action

**Instance style** — construct first, then call `run()`:

```php
$post = (new CreateNewPost(title: 'Hello', body: 'World'))->run();
```

**Static style** — constructor arguments are passed directly to `run()`:

```php
$post = CreateNewPost::run(title: 'Hello', body: 'World');
```

Both styles are equivalent.

## Dependency Injection

Type-hint services in `handle()` and Laravel's container injects them automatically:

```php
namespace App\Actions;

use App\Mail\PostCreated;
use App\Models\Post;
use Illuminate\Contracts\Mail\Mailer;
use Laraditz\Action\Action;

class PublishPost extends Action
{
    public function __construct(
        public string $title,
        public string $body,
        public string $authorEmail,
    ) {}

    public function handle(Mailer $mailer): Post
    {
        $post = Post::create($this->data());

        $mailer->to($this->authorEmail)->send(new PostCreated($post));

        return $post;
    }
}
```

```php
// $mailer is resolved from the container automatically
PublishPost::run(
    title: 'My Post',
    body: 'Content here',
    authorEmail: 'author@example.com',
);
```

## Queue Support

Make an action queueable by implementing `ShouldQueue` and adding the standard Laravel queue traits. Do **not** add `Dispatchable` — the base `Action` class already provides `dispatch()`.

```php
namespace App\Actions;

use App\Mail\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laraditz\Action\Action;

class SendWelcomeEmail extends Action implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public string $email,
        public string $name,
    ) {}

    public function handle(Mailer $mailer): void
    {
        $mailer->to($this->email)->send(new WelcomeEmail($this->name));
    }
}
```

**Dispatching to the queue:**

```php
// Using the base class static dispatch()
SendWelcomeEmail::dispatch(email: 'user@example.com', name: 'Alice');

// Using Laravel's global helper
dispatch(new SendWelcomeEmail(email: 'user@example.com', name: 'Alice'));
```

When the queue worker processes the job, Laravel's `CallQueuedHandler` calls `handle()` through the container, so type-hinted dependencies are injected automatically — the same as when running synchronously.

> **Note:** Do not add `use Illuminate\Foundation\Bus\Dispatchable` to your action class. The base `Action` class already provides `dispatch()`, and adding the trait will cause a conflict.

## The `data()` Helper

`data()` returns all constructor-promoted properties as a key–value array, which is handy for mass-assignment:

```php
public function handle(): Post
{
    // Returns ['title' => '...', 'body' => '...']
    return Post::create($this->data());
}
```

Actions with no constructor return an empty array from `data()`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email raditzfarhan@gmail.com instead of using the issue tracker.

## Credits

- [Raditz Farhan](https://github.com/raditzfarhan)

## License

MIT. Please see the [license file](LICENSE) for more information.
