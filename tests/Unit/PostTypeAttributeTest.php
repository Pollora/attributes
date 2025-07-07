<?php

declare(strict_types=1);

use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\RegisterMetaBoxCb;
use Pollora\Attributes\PostType\Supports;

/**
 * PostType Attribute Tests
 *
 * Tests for the simplified PostType attributes that now only contain properties
 * and delegate all processing logic to the PostTypeDiscovery service.
 */

// Test class for PostType attributes
#[PostType('test-product', 'Product', 'Products')]
#[HasArchive(true)]
#[Supports(['title', 'editor', 'thumbnail'])]
#[MenuIcon('dashicons-products')]
#[PubliclyQueryable(true)]
class TestProduct
{
    #[AdminCol('price', 'Price')]
    public function getPriceColumn(int $postId): string
    {
        return '$99.99';
    }

    #[AdminCol('stock', 'Stock Status')]
    public function getStockColumn(int $postId): string
    {
        return 'In Stock';
    }

    #[RegisterMetaBoxCb]
    public function registerProductMetaBoxes($post): void
    {
        // Meta box registration logic
    }

    public function getProductDetails(): array
    {
        return ['name' => 'Test Product'];
    }
}

it('creates PostType attribute with all parameters', function () {
    $postType = new PostType('custom-slug', 'Custom Type', 'Custom Types');

    expect($postType->slug)->toBe('custom-slug');
    expect($postType->singular)->toBe('Custom Type');
    expect($postType->plural)->toBe('Custom Types');
});

it('creates PostType attribute with only slug', function () {
    $postType = new PostType('events');

    expect($postType->slug)->toBe('events');
    expect($postType->singular)->toBeNull();
    expect($postType->plural)->toBeNull();
});

it('creates PostType attribute with no parameters', function () {
    $postType = new PostType;

    expect($postType->slug)->toBeNull();
    expect($postType->singular)->toBeNull();
    expect($postType->plural)->toBeNull();
});

it('stores PostType properties as readonly', function () {
    $postType = new PostType('test');

    expect($postType->slug)->toBe('test');

    // Verify properties are readonly
    $reflection = new ReflectionClass($postType);
    $slugProperty = $reflection->getProperty('slug');
    $singularProperty = $reflection->getProperty('singular');
    $pluralProperty = $reflection->getProperty('plural');

    expect($slugProperty->isReadOnly())->toBeTrue();
    expect($singularProperty->isReadOnly())->toBeTrue();
    expect($pluralProperty->isReadOnly())->toBeTrue();
});

it('creates HasArchive attribute with default value', function () {
    $hasArchive = new HasArchive;

    expect($hasArchive->value)->toBeTrue();
});

it('creates HasArchive attribute with custom slug', function () {
    $hasArchive = new HasArchive('custom-archive');

    expect($hasArchive->value)->toBe('custom-archive');
});

it('creates HasArchive attribute disabled', function () {
    $hasArchive = new HasArchive(false);

    expect($hasArchive->value)->toBeFalse();
});

it('creates Supports attribute with default features', function () {
    $supports = new Supports;

    expect($supports->features)->toBe(['title', 'editor']);
});

it('creates Supports attribute with custom features', function () {
    $features = ['title', 'editor', 'thumbnail', 'excerpt'];
    $supports = new Supports($features);

    expect($supports->features)->toBe($features);
});

it('creates MenuIcon attribute with dashicon', function () {
    $menuIcon = new MenuIcon('dashicons-admin-post');

    expect($menuIcon->value)->toBe('dashicons-admin-post');
});

it('creates MenuIcon attribute with custom URL', function () {
    $menuIcon = new MenuIcon('https://example.com/icon.png');

    expect($menuIcon->value)->toBe('https://example.com/icon.png');
});

it('creates PubliclyQueryable attribute with default value', function () {
    $publiclyQueryable = new PubliclyQueryable;

    expect($publiclyQueryable->value)->toBeTrue();
});

it('creates PubliclyQueryable attribute disabled', function () {
    $publiclyQueryable = new PubliclyQueryable(false);

    expect($publiclyQueryable->value)->toBeFalse();
});

it('creates AdminCol attribute with all parameters', function () {
    $adminCol = new AdminCol(
        'price',
        'Product Price',
        sortable: true,
        width: 120,
        titleIcon: 'dashicons-money',
        dateFormat: 'd/m/Y',
        link: 'edit',
        cap: 'edit_posts',
        default: 'ASC'
    );

    expect($adminCol->key)->toBe('price');
    expect($adminCol->title)->toBe('Product Price');
    expect($adminCol->width)->toBe(120);
    expect($adminCol->sortable)->toBeTrue();
    expect($adminCol->titleIcon)->toBe('dashicons-money');
    expect($adminCol->dateFormat)->toBe('d/m/Y');
    expect($adminCol->link)->toBe('edit');
    expect($adminCol->cap)->toBe('edit_posts');
    expect($adminCol->default)->toBe('ASC');
});

it('creates AdminCol attribute with minimal parameters', function () {
    $adminCol = new AdminCol('title', 'Title');

    expect($adminCol->key)->toBe('title');
    expect($adminCol->title)->toBe('Title');
    expect($adminCol->width)->toBeNull();
    expect($adminCol->sortable)->toBeFalse();
    expect($adminCol->titleIcon)->toBeNull();
    expect($adminCol->dateFormat)->toBeNull();
    expect($adminCol->link)->toBeNull();
    expect($adminCol->cap)->toBeNull();
    expect($adminCol->default)->toBeNull();
});

it('creates AdminCol attribute for meta fields', function () {
    $adminCol = new AdminCol(
        'custom_price',
        'Price',
        sortable: 'meta_value_num',
        metaKey: 'product_price'
    );

    expect($adminCol->key)->toBe('custom_price');
    expect($adminCol->title)->toBe('Price');
    expect($adminCol->sortable)->toBe('meta_value_num');
    expect($adminCol->metaKey)->toBe('product_price');
});

it('creates AdminCol attribute for taxonomy fields', function () {
    $adminCol = new AdminCol(
        'categories',
        'Categories',
        taxonomy: 'product_category'
    );

    expect($adminCol->key)->toBe('categories');
    expect($adminCol->title)->toBe('Categories');
    expect($adminCol->taxonomy)->toBe('product_category');
});

it('creates AdminCol attribute for featured image', function () {
    $adminCol = new AdminCol(
        'image',
        'Featured Image',
        featuredImage: 'thumbnail',
        width: 80
    );

    expect($adminCol->key)->toBe('image');
    expect($adminCol->title)->toBe('Featured Image');
    expect($adminCol->featuredImage)->toBe('thumbnail');
    expect($adminCol->width)->toBe(80);
});

it('creates RegisterMetaBoxCb attribute', function () {
    $registerMetaBoxCb = new RegisterMetaBoxCb;

    expect($registerMetaBoxCb)->toBeInstanceOf(RegisterMetaBoxCb::class);
});

it('has correct PHP attribute configurations', function () {
    // Test PostType attribute configuration
    $postTypeReflection = new ReflectionClass(PostType::class);
    $postTypeAttributes = $postTypeReflection->getAttributes(Attribute::class);

    expect($postTypeAttributes)->toHaveCount(1);
    $postTypeAttribute = $postTypeAttributes[0]->newInstance();
    expect($postTypeAttribute->flags)->toBe(Attribute::TARGET_CLASS);

    // Test AdminCol attribute configuration
    $adminColReflection = new ReflectionClass(AdminCol::class);
    $adminColAttributes = $adminColReflection->getAttributes(Attribute::class);

    expect($adminColAttributes)->toHaveCount(1);
    $adminColAttribute = $adminColAttributes[0]->newInstance();
    expect($adminColAttribute->flags)->toBe(Attribute::TARGET_METHOD);

    // Test RegisterMetaBoxCb attribute configuration
    $registerMetaBoxCbReflection = new ReflectionClass(RegisterMetaBoxCb::class);
    $registerMetaBoxCbAttributes = $registerMetaBoxCbReflection->getAttributes(Attribute::class);

    expect($registerMetaBoxCbAttributes)->toHaveCount(1);
    $registerMetaBoxCbAttribute = $registerMetaBoxCbAttributes[0]->newInstance();
    expect($registerMetaBoxCbAttribute->flags)->toBe(Attribute::TARGET_METHOD);
});

it('test class has correct attributes applied', function () {
    $reflection = new ReflectionClass(TestProduct::class);

    // Check class-level attributes
    $postTypeAttrs = $reflection->getAttributes(PostType::class);
    expect($postTypeAttrs)->toHaveCount(1);

    $hasArchiveAttrs = $reflection->getAttributes(HasArchive::class);
    expect($hasArchiveAttrs)->toHaveCount(1);

    $supportsAttrs = $reflection->getAttributes(Supports::class);
    expect($supportsAttrs)->toHaveCount(1);

    // Check method-level attributes
    $priceMethod = $reflection->getMethod('getPriceColumn');
    $priceAdminColAttrs = $priceMethod->getAttributes(AdminCol::class);
    expect($priceAdminColAttrs)->toHaveCount(1);

    $stockMethod = $reflection->getMethod('getStockColumn');
    $stockAdminColAttrs = $stockMethod->getAttributes(AdminCol::class);
    expect($stockAdminColAttrs)->toHaveCount(1);

    $metaBoxMethod = $reflection->getMethod('registerProductMetaBoxes');
    $metaBoxAttrs = $metaBoxMethod->getAttributes(RegisterMetaBoxCb::class);
    expect($metaBoxAttrs)->toHaveCount(1);
});

it('attribute values can be extracted correctly', function () {
    $reflection = new ReflectionClass(TestProduct::class);

    // Extract PostType attribute values
    $postTypeAttr = $reflection->getAttributes(PostType::class)[0];
    $postType = $postTypeAttr->newInstance();
    expect($postType->slug)->toBe('test-product');
    expect($postType->singular)->toBe('Product');
    expect($postType->plural)->toBe('Products');

    // Extract HasArchive attribute value
    $hasArchiveAttr = $reflection->getAttributes(HasArchive::class)[0];
    $hasArchive = $hasArchiveAttr->newInstance();
    expect($hasArchive->value)->toBeTrue();

    // Extract Supports attribute values
    $supportsAttr = $reflection->getAttributes(Supports::class)[0];
    $supports = $supportsAttr->newInstance();
    expect($supports->features)->toBe(['title', 'editor', 'thumbnail']);

    // Extract AdminCol attribute values
    $priceMethod = $reflection->getMethod('getPriceColumn');
    $adminColAttr = $priceMethod->getAttributes(AdminCol::class)[0];
    $adminCol = $adminColAttr->newInstance();
    expect($adminCol->key)->toBe('price');
    expect($adminCol->title)->toBe('Price');
});

it('accepts all types without validation', function () {
    // No validation should happen in attribute constructors
    // All validation is now handled by PostTypeDiscovery

    // PostType with any values
    expect(fn () => new PostType('', '', ''))->not->toThrow(Exception::class);
    expect(fn () => new PostType('invalid slug', 'invalid name', 'invalid plural'))->not->toThrow(Exception::class);

    // HasArchive with any values
    expect(fn () => new HasArchive(''))->not->toThrow(Exception::class);
    expect(fn () => new HasArchive('invalid-archive'))->not->toThrow(Exception::class);

    // Supports with any array
    expect(fn () => new Supports([]))->not->toThrow(Exception::class);
    expect(fn () => new Supports(['invalid-feature']))->not->toThrow(Exception::class);

    // MenuIcon with any string
    expect(fn () => new MenuIcon(''))->not->toThrow(Exception::class);
    expect(fn () => new MenuIcon('invalid-icon'))->not->toThrow(Exception::class);

    // AdminCol with any values
    expect(fn () => new AdminCol('', ''))->not->toThrow(Exception::class);
});
