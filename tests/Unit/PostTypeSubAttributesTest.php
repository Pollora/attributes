<?php

declare(strict_types=1);

use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\Supports;

/**
 * PostType Sub-Attributes Tests
 *
 * Tests for the simplified PostType sub-attributes that demonstrate how
 * multiple attributes can be combined on a single class and how method-level
 * attributes work alongside class-level attributes.
 */

// Example class using PostType with sub-attributes
#[PostType('product')]
#[HasArchive('products-archive')]
#[Supports(['title', 'editor', 'thumbnail', 'excerpt'])]
#[MenuIcon('dashicons-cart')]
class ProductWithSubAttributes
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

    #[AdminCol('category', 'Category')]
    public function getCategoryColumn(int $postId): string
    {
        return 'Electronics';
    }

    public function getProductName(): string
    {
        return 'Product';
    }
}

it('detects all class-level PostType attributes', function () {
    $reflection = new ReflectionClass(ProductWithSubAttributes::class);

    // Should detect PostType main attribute
    $postTypeAttrs = $reflection->getAttributes(PostType::class);
    expect($postTypeAttrs)->toHaveCount(1);

    // Should detect HasArchive sub-attribute
    $hasArchiveAttrs = $reflection->getAttributes(HasArchive::class);
    expect($hasArchiveAttrs)->toHaveCount(1);

    // Should detect Supports sub-attribute
    $supportsAttrs = $reflection->getAttributes(Supports::class);
    expect($supportsAttrs)->toHaveCount(1);

    // Should detect MenuIcon sub-attribute
    $menuIconAttrs = $reflection->getAttributes(MenuIcon::class);
    expect($menuIconAttrs)->toHaveCount(1);
});

it('detects all method-level AdminCol attributes', function () {
    $reflection = new ReflectionClass(ProductWithSubAttributes::class);

    // Check price method
    $priceMethod = $reflection->getMethod('getPriceColumn');
    $priceAttrs = $priceMethod->getAttributes(AdminCol::class);
    expect($priceAttrs)->toHaveCount(1);

    // Check stock method
    $stockMethod = $reflection->getMethod('getStockColumn');
    $stockAttrs = $stockMethod->getAttributes(AdminCol::class);
    expect($stockAttrs)->toHaveCount(1);

    // Check category method
    $categoryMethod = $reflection->getMethod('getCategoryColumn');
    $categoryAttrs = $categoryMethod->getAttributes(AdminCol::class);
    expect($categoryAttrs)->toHaveCount(1);

    // Check that method without attribute has no AdminCol attributes
    $nameMethod = $reflection->getMethod('getProductName');
    $nameAttrs = $nameMethod->getAttributes(AdminCol::class);
    expect($nameAttrs)->toHaveCount(0);
});

it('extracts correct values from class-level attributes', function () {
    $reflection = new ReflectionClass(ProductWithSubAttributes::class);

    // Extract PostType attribute
    $postTypeAttr = $reflection->getAttributes(PostType::class)[0];
    $postType = $postTypeAttr->newInstance();
    expect($postType->slug)->toBe('product');
    expect($postType->singular)->toBeNull();
    expect($postType->plural)->toBeNull();

    // Extract HasArchive attribute
    $hasArchiveAttr = $reflection->getAttributes(HasArchive::class)[0];
    $hasArchive = $hasArchiveAttr->newInstance();
    expect($hasArchive->value)->toBe('products-archive');

    // Extract Supports attribute
    $supportsAttr = $reflection->getAttributes(Supports::class)[0];
    $supports = $supportsAttr->newInstance();
    expect($supports->features)->toBe(['title', 'editor', 'thumbnail', 'excerpt']);

    // Extract MenuIcon attribute
    $menuIconAttr = $reflection->getAttributes(MenuIcon::class)[0];
    $menuIcon = $menuIconAttr->newInstance();
    expect($menuIcon->value)->toBe('dashicons-cart');
});

it('extracts correct values from method-level AdminCol attributes', function () {
    $reflection = new ReflectionClass(ProductWithSubAttributes::class);

    // Extract price AdminCol
    $priceMethod = $reflection->getMethod('getPriceColumn');
    $priceAttr = $priceMethod->getAttributes(AdminCol::class)[0];
    $priceAdminCol = $priceAttr->newInstance();
    expect($priceAdminCol->key)->toBe('price');
    expect($priceAdminCol->title)->toBe('Price');
    expect($priceAdminCol->width)->toBeNull(); // Default value
    expect($priceAdminCol->sortable)->toBeFalse(); // Default value

    // Extract stock AdminCol
    $stockMethod = $reflection->getMethod('getStockColumn');
    $stockAttr = $stockMethod->getAttributes(AdminCol::class)[0];
    $stockAdminCol = $stockAttr->newInstance();
    expect($stockAdminCol->key)->toBe('stock');
    expect($stockAdminCol->title)->toBe('Stock Status');
    expect($stockAdminCol->width)->toBeNull(); // Default value
    expect($stockAdminCol->sortable)->toBeFalse(); // Default value

    // Extract category AdminCol
    $categoryMethod = $reflection->getMethod('getCategoryColumn');
    $categoryAttr = $categoryMethod->getAttributes(AdminCol::class)[0];
    $categoryAdminCol = $categoryAttr->newInstance();
    expect($categoryAdminCol->key)->toBe('category');
    expect($categoryAdminCol->title)->toBe('Category');
    expect($categoryAdminCol->width)->toBeNull(); // Default value
    expect($categoryAdminCol->sortable)->toBeFalse(); // Default value
});

it('supports multiple AdminCol attributes on different methods', function () {
    $reflection = new ReflectionClass(ProductWithSubAttributes::class);
    $adminColMethods = [];

    foreach ($reflection->getMethods() as $method) {
        $adminColAttrs = $method->getAttributes(AdminCol::class);
        if (! empty($adminColAttrs)) {
            $adminColMethods[$method->getName()] = $adminColAttrs[0]->newInstance();
        }
    }

    expect($adminColMethods)->toHaveCount(3);
    expect($adminColMethods)->toHaveKeys(['getPriceColumn', 'getStockColumn', 'getCategoryColumn']);

    // Verify each column has unique key
    $keys = array_map(fn ($adminCol) => $adminCol->key, $adminColMethods);
    expect($keys)->toBe(['getPriceColumn' => 'price', 'getStockColumn' => 'stock', 'getCategoryColumn' => 'category']);
    expect(array_unique($keys))->toHaveCount(3); // All keys are unique
});

it('demonstrates attribute composition pattern', function () {
    // This test demonstrates how the new system works:
    // 1. PostType attribute defines the main post type
    // 2. Sub-attributes (HasArchive, Supports, MenuIcon) configure post type settings
    // 3. Method-level attributes (AdminCol) define callbacks and additional functionality

    $reflection = new ReflectionClass(ProductWithSubAttributes::class);

    // Main post type definition
    $postTypeAttr = $reflection->getAttributes(PostType::class)[0]->newInstance();
    expect($postTypeAttr->slug)->toBe('product');

    // Archive configuration
    $hasArchiveAttr = $reflection->getAttributes(HasArchive::class)[0]->newInstance();
    expect($hasArchiveAttr->value)->toBe('products-archive');

    // Features configuration
    $supportsAttr = $reflection->getAttributes(Supports::class)[0]->newInstance();
    expect($supportsAttr->features)->toContain('title', 'editor', 'thumbnail', 'excerpt');

    // Admin UI configuration
    $menuIconAttr = $reflection->getAttributes(MenuIcon::class)[0]->newInstance();
    expect($menuIconAttr->value)->toBe('dashicons-cart');

    // Admin columns configuration (method-level)
    $columnMethods = [];
    foreach ($reflection->getMethods() as $method) {
        $adminColAttrs = $method->getAttributes(AdminCol::class);
        if (! empty($adminColAttrs)) {
            $columnMethods[] = [
                'method' => $method->getName(),
                'column' => $adminColAttrs[0]->newInstance(),
            ];
        }
    }

    expect($columnMethods)->toHaveCount(3);

    // Verify the composition creates a complete post type configuration
    $allClassAttributes = $reflection->getAttributes();
    $allMethodAttributes = [];
    foreach ($reflection->getMethods() as $method) {
        $allMethodAttributes = array_merge($allMethodAttributes, $method->getAttributes());
    }

    // Should have class-level attributes for configuration
    expect($allClassAttributes)->toHaveCount(4); // PostType + 3 sub-attributes

    // Should have method-level attributes for callbacks
    expect($allMethodAttributes)->toHaveCount(3); // 3 AdminCol attributes
});

it('attributes have correct target configurations', function () {
    // Verify class-level attributes target classes
    $classLevelAttributes = [
        PostType::class,
        HasArchive::class,
        Supports::class,
        MenuIcon::class,
    ];

    foreach ($classLevelAttributes as $attributeClass) {
        $reflection = new ReflectionClass($attributeClass);
        $attributes = $reflection->getAttributes(Attribute::class);
        expect($attributes)->toHaveCount(1);

        $attribute = $attributes[0]->newInstance();
        expect($attribute->flags)->toBe(Attribute::TARGET_CLASS);
    }

    // Verify method-level attributes target methods
    $methodLevelAttributes = [
        AdminCol::class,
    ];

    foreach ($methodLevelAttributes as $attributeClass) {
        $reflection = new ReflectionClass($attributeClass);
        $attributes = $reflection->getAttributes(Attribute::class);
        expect($attributes)->toHaveCount(1);

        $attribute = $attributes[0]->newInstance();
        expect($attribute->flags)->toBe(Attribute::TARGET_METHOD);
    }
});

it('demonstrates no validation in attributes', function () {
    // All attributes should accept any values without validation
    // Validation will be handled by PostTypeDiscovery

    expect(fn () => new PostType('', '', ''))->not->toThrow(Exception::class);
    expect(fn () => new HasArchive(''))->not->toThrow(Exception::class);
    expect(fn () => new Supports([]))->not->toThrow(Exception::class);
    expect(fn () => new MenuIcon(''))->not->toThrow(Exception::class);
    expect(fn () => new AdminCol('', ''))->not->toThrow(Exception::class);

    // Even completely invalid values should not throw
    expect(fn () => new PostType('invalid slug with spaces', 'inv@lid', 'pl{ur}al'))->not->toThrow(Exception::class);
    expect(fn () => new HasArchive(123))->not->toThrow(Exception::class); // Wrong type
    expect(fn () => new Supports(['invalid-feature', '', null]))->not->toThrow(Exception::class);
    expect(fn () => new MenuIcon(null))->not->toThrow(Exception::class); // Wrong type
});
