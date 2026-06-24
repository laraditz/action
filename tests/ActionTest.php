<?php

namespace Laraditz\Action\Tests;

use BadMethodCallException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laraditz\Action\Action;

// --- Fixtures ---

class SimpleAction extends Action
{
    public function __construct(public readonly string $value = 'hello') {}

    public function handle(): string
    {
        return $this->value;
    }
}

class NoConstructorAction extends Action
{
    public function handle(): string
    {
        return 'no-constructor';
    }
}

class InjectableService
{
    public function greet(string $name): string
    {
        return "Hello, {$name}!";
    }
}

class InjectableAction extends Action
{
    public function __construct(public readonly string $name = 'World') {}

    public function handle(InjectableService $service): string
    {
        return $service->greet($this->name);
    }
}

class QueueableAction extends Action implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(public readonly string $payload = '') {}

    public function handle(): void {}
}

// --- Tests ---

class ActionTest extends TestCase
{
    public function test_instance_run_calls_handle(): void
    {
        $action = new SimpleAction('world');
        $result = $action->run();

        $this->assertSame('world', $result);
    }

    public function test_static_run_creates_instance_and_calls_handle(): void
    {
        $result = SimpleAction::run('static');

        $this->assertSame('static', $result);
    }

    public function test_instance_run_injects_dependencies_into_handle(): void
    {
        $action = new InjectableAction('Laravel');
        $result = $action->run();

        $this->assertSame('Hello, Laravel!', $result);
    }

    public function test_static_run_injects_dependencies_into_handle(): void
    {
        $result = InjectableAction::run('World');

        $this->assertSame('Hello, World!', $result);
    }

    public function test_data_returns_constructor_parameters(): void
    {
        $action = new SimpleAction('test-value');
        $data = $action->data();

        $this->assertSame(['value' => 'test-value'], $data);
    }

    public function test_data_returns_empty_array_when_no_constructor(): void
    {
        $action = new NoConstructorAction();
        $data = $action->data();

        $this->assertSame([], $data);
    }

    public function test_dispatch_returns_pending_dispatch(): void
    {
        $pending = QueueableAction::dispatch('my-payload');

        $this->assertInstanceOf(PendingDispatch::class, $pending);
    }

    public function test_unknown_static_method_throws_bad_method_call_exception(): void
    {
        $this->expectException(BadMethodCallException::class);

        SimpleAction::unknownMethod();
    }
}
