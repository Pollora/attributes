<p align="center">
  <a href="https://pollora.dev">
    <img src="https://raw.githubusercontent.com/Pollora/.github/main/brand/banners/attributes.png" width="100%" alt="Pollora Attributes: declare post types, taxonomies, hooks and REST routes as PHP attributes">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/pollora/attributes"><img src="https://img.shields.io/packagist/v/pollora/attributes" alt="Latest version"></a>
  <a href="https://packagist.org/packages/pollora/attributes"><img src="https://img.shields.io/packagist/dt/pollora/attributes" alt="Total downloads"></a>
  <a href="https://github.com/Pollora/attributes/actions/workflows/tests.yml"><img src="https://github.com/Pollora/attributes/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
  <a href="LICENSE"><img src="https://img.shields.io/github/license/Pollora/attributes" alt="License"></a>
</p>

The PHP attributes that declare WordPress components as code: `#[PostType]`, `#[Taxonomy]`, `#[Action]`, `#[Filter]`, `#[WpRestRoute]`, `#[Meta]`, `#[Role]`, `#[Schedule]`, `#[Ajax]`, `#[Ability]`, `#[BlockBinding]`, `#[WpCli]` and the attributes that refine them.

They are declarations only. Each attribute holds its values and, for post types and taxonomies, says which registration arguments it sets; registering is left to the code that reads them. A plugin can declare its components without installing a framework, and an IDE or a static analyser can read them alone.

> Part of [Pollora](https://pollora.dev), the Laravel framework for WordPress, which discovers these attributes and registers what they declare. See [Auto-discovery](https://pollora.dev/core-concepts/auto-discovery/) and the [post type attributes reference](https://pollora.dev/content/post-types-reference/).

## Installation

```bash
composer require pollora/attributes
```

Requires PHP 8.3+ and `mbstring`. It brings [`pollora/ajax`](https://github.com/Pollora/ajax) and [`pollora/abilities`](https://github.com/Pollora/abilities), whose `AjaxAccess` and `Behaviour` enumerations `#[Ajax]` and `#[Ability]` take; both depend on PHP alone.

## Declaring

```php
use Pollora\Attributes\Action;
use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\ShowInRest;
use Pollora\Attributes\PostType\Supports;

#[PostType('book')]
#[HasArchive('library')]
#[ShowInRest]
#[Supports(['title', 'editor', 'thumbnail'])]
final class Book
{
    #[AdminCol('isbn', 'ISBN', sortable: true)]
    public function isbnColumn(int $postId): string
    {
        return (string) get_post_meta($postId, 'isbn', true);
    }

    #[Action('save_post_book')]
    public function onSave(int $postId): void {}
}
```

## Reading

Post type and taxonomy attributes implement `ConfiguresArguments`: given the registration arguments gathered so far, they return them with their own contribution. Method attributes such as `#[AdminCol]` or `#[RegisterMetaBoxCb]` implement `ConfiguresArgumentsWithMethod` and are handed the decorated method as a callback.

```php
use Pollora\Attributes\Contracts\ConfiguresArguments;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;
use Pollora\Attributes\PostType;

$class = new ReflectionClass(Book::class);
$postType = $class->getAttributes(PostType::class)[0]->newInstance();
$arguments = [];

foreach ($class->getAttributes() as $attribute) {
    $instance = $attribute->newInstance();

    if ($instance instanceof ConfiguresArguments) {
        $arguments = $instance->configureArguments($arguments);
    }
}

foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    foreach ($method->getAttributes() as $attribute) {
        $instance = $attribute->newInstance();

        if ($instance instanceof ConfiguresArgumentsWithMethod) {
            $arguments = $instance->configureArguments($arguments, [new Book, $method->getName()]);
        }
    }
}

register_post_type($postType->resolveSlug(Book::class), $arguments);
// ['has_archive' => 'library', 'show_in_rest' => true, 'supports' => [...], 'admin_cols' => ['isbn' => [...]]]
```

`resolveSlug()` derives the slug from the class name when none is given (`Book` → `book`, kebab case, 20 characters at most), as Pollora does. The other attributes are plain values: `#[Action('init', priority: 20)]` exposes `$hook` and `$priority`.

## Public API

The attribute classes, their constructor parameters and public properties, and the `Pollora\Attributes\Contracts\*` interfaces follow semantic versioning. `Pollora\Attributes\Support\*` is internal.

The package also carries the value types the attributes take, under the class names Pollora has always used: `Pollora\Schedule\Every` and `Interval`, `Pollora\Role\Domain\Enums\Access`, `Pollora\Meta\Domain\Enums\Control` and `Pollora\BlockBinding\Domain\Enums\BindingFieldType`.

## Testing

```bash
composer test   # unit tests, PHPStan, Pint
```

## License

MIT. See [LICENSE](LICENSE).
