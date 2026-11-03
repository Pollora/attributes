<?php

declare(strict_types=1);

use Pollora\Attributes\WpRestRoute\Method;
use Pollora\Attributes\WpRestRoute\Permission;

it('accepts every supported HTTP verb, in any case', function (string $verb): void {
    expect((new Method($verb))->getMethods())->toBe([$verb]);
})->with(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'get', 'Patch']);

it('wraps a single verb in a list and keeps a list as it is', function (): void {
    expect((new Method('GET'))->getMethods())->toBe(['GET'])
        ->and((new Method(['GET', 'POST']))->getMethods())->toBe(['GET', 'POST']);
});

it('refuses a verb a REST route cannot use', function (array|string $methods): void {
    new Method($methods);
})->with([
    'single' => ['HEAD'],
    'in a list' => [['GET', 'TRACE']],
    'empty' => [''],
])->throws(InvalidArgumentException::class, 'Allowed methods are: GET, POST, PUT, DELETE, PATCH');

it('names the invalid verb in the message', function (): void {
    new Method(['GET', 'OPTIONS']);
})->throws(InvalidArgumentException::class, 'Invalid HTTP method "OPTIONS"');

it('keeps the permission that replaces the route\'s', function (): void {
    $permission = new class implements Permission
    {
        public function allow(WP_REST_Request $request): bool
        {
            return true;
        }
    };

    expect((new Method('GET', $permission))->permissionCallback)->toBe($permission)
        ->and((new Method('GET', $permission::class))->permissionCallback)->toBe($permission::class)
        ->and((new Method('GET'))->permissionCallback)->toBeNull();
});
