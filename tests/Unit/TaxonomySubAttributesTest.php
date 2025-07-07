<?php

declare(strict_types=1);

use Pollora\Attributes\Taxonomy;
use Pollora\Attributes\Taxonomy\Hierarchical;
use Pollora\Attributes\Taxonomy\MetaBoxCb;
use Pollora\Attributes\Taxonomy\MetaBoxSanitizeCb;
use Pollora\Attributes\Taxonomy\ObjectType;
use Pollora\Attributes\Taxonomy\PublicTaxonomy;
use Pollora\Attributes\Taxonomy\ShowInRest;
use Pollora\Attributes\Taxonomy\ShowUI;
use Pollora\Attributes\Taxonomy\UpdateCountCallback;

/**
 * Taxonomy Sub-Attributes Tests
 *
 * Tests for the simplified Taxonomy sub-attributes that demonstrate how
 * multiple attributes can be combined on a single class and how method-level
 * attributes work alongside class-level attributes.
 */

// Example class using Taxonomy with sub-attributes
#[Taxonomy('product-category')]
#[Hierarchical(true)]
#[PublicTaxonomy(true)]
#[ShowUI(true)]
#[ShowInRest(true)]
#[ObjectType(['product', 'variation'])]
class ProductCategoryWithSubAttributes
{
    #[MetaBoxCb]
    public function customMetaBox($post, $box): void
    {
        // Custom meta box implementation
        echo '<p>Custom taxonomy meta box</p>';
    }

    #[UpdateCountCallback]
    public function updateTermCount($terms, $taxonomy): void
    {
        // Custom count update logic
        foreach ($terms as $term) {
            // Update count logic here
        }
    }

    #[MetaBoxSanitizeCb]
    public function sanitizeTerms($terms): array
    {
        // Sanitize the terms
        return array_map('sanitize_text_field', (array) $terms);
    }

    public function getTaxonomyName(): string
    {
        return 'Product Category';
    }
}

it('detects all class-level Taxonomy attributes', function () {
    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);

    // Should detect Taxonomy main attribute
    $taxonomyAttrs = $reflection->getAttributes(Taxonomy::class);
    expect($taxonomyAttrs)->toHaveCount(1);

    // Should detect Hierarchical sub-attribute
    $hierarchicalAttrs = $reflection->getAttributes(Hierarchical::class);
    expect($hierarchicalAttrs)->toHaveCount(1);

    // Should detect PublicTaxonomy sub-attribute
    $publicAttrs = $reflection->getAttributes(PublicTaxonomy::class);
    expect($publicAttrs)->toHaveCount(1);

    // Should detect ShowUI sub-attribute
    $showUIAttrs = $reflection->getAttributes(ShowUI::class);
    expect($showUIAttrs)->toHaveCount(1);

    // Should detect ShowInRest sub-attribute
    $showInRestAttrs = $reflection->getAttributes(ShowInRest::class);
    expect($showInRestAttrs)->toHaveCount(1);

    // Should detect ObjectType sub-attribute
    $objectTypeAttrs = $reflection->getAttributes(ObjectType::class);
    expect($objectTypeAttrs)->toHaveCount(1);
});

it('detects all method-level callback attributes', function () {
    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);

    // Check custom meta box method
    $metaBoxMethod = $reflection->getMethod('customMetaBox');
    $metaBoxAttrs = $metaBoxMethod->getAttributes(MetaBoxCb::class);
    expect($metaBoxAttrs)->toHaveCount(1);

    // Check update count method
    $updateCountMethod = $reflection->getMethod('updateTermCount');
    $updateCountAttrs = $updateCountMethod->getAttributes(UpdateCountCallback::class);
    expect($updateCountAttrs)->toHaveCount(1);

    // Check sanitize method
    $sanitizeMethod = $reflection->getMethod('sanitizeTerms');
    $sanitizeAttrs = $sanitizeMethod->getAttributes(MetaBoxSanitizeCb::class);
    expect($sanitizeAttrs)->toHaveCount(1);

    // Check that method without attribute has no callback attributes
    $nameMethod = $reflection->getMethod('getTaxonomyName');
    $nameCallbackAttrs = array_merge(
        $nameMethod->getAttributes(MetaBoxCb::class),
        $nameMethod->getAttributes(UpdateCountCallback::class),
        $nameMethod->getAttributes(MetaBoxSanitizeCb::class)
    );
    expect($nameCallbackAttrs)->toHaveCount(0);
});

it('extracts correct values from class-level attributes', function () {
    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);

    // Extract Taxonomy attribute
    $taxonomyAttr = $reflection->getAttributes(Taxonomy::class)[0];
    $taxonomy = $taxonomyAttr->newInstance();
    expect($taxonomy->slug)->toBe('product-category');
    expect($taxonomy->singular)->toBeNull();
    expect($taxonomy->plural)->toBeNull();
    expect($taxonomy->objectType)->toBeNull();

    // Test that attributes can be instantiated successfully
    $hierarchicalAttr = $reflection->getAttributes(Hierarchical::class)[0];
    $hierarchical = $hierarchicalAttr->newInstance();
    expect($hierarchical)->toBeInstanceOf(Hierarchical::class);

    $publicAttr = $reflection->getAttributes(PublicTaxonomy::class)[0];
    $public = $publicAttr->newInstance();
    expect($public)->toBeInstanceOf(PublicTaxonomy::class);

    $showUIAttr = $reflection->getAttributes(ShowUI::class)[0];
    $showUI = $showUIAttr->newInstance();
    expect($showUI)->toBeInstanceOf(ShowUI::class);

    $showInRestAttr = $reflection->getAttributes(ShowInRest::class)[0];
    $showInRest = $showInRestAttr->newInstance();
    expect($showInRest)->toBeInstanceOf(ShowInRest::class);

    $objectTypeAttr = $reflection->getAttributes(ObjectType::class)[0];
    $objectType = $objectTypeAttr->newInstance();
    expect($objectType)->toBeInstanceOf(ObjectType::class);
});

it('extracts correct values from method-level callback attributes', function () {
    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);

    // Extract MetaBoxCb
    $metaBoxMethod = $reflection->getMethod('customMetaBox');
    $metaBoxAttr = $metaBoxMethod->getAttributes(MetaBoxCb::class)[0];
    $metaBoxCb = $metaBoxAttr->newInstance();
    expect($metaBoxCb)->toBeInstanceOf(MetaBoxCb::class);

    // Extract UpdateCountCallback
    $updateCountMethod = $reflection->getMethod('updateTermCount');
    $updateCountAttr = $updateCountMethod->getAttributes(UpdateCountCallback::class)[0];
    $updateCountCb = $updateCountAttr->newInstance();
    expect($updateCountCb)->toBeInstanceOf(UpdateCountCallback::class);

    // Extract MetaBoxSanitizeCb
    $sanitizeMethod = $reflection->getMethod('sanitizeTerms');
    $sanitizeAttr = $sanitizeMethod->getAttributes(MetaBoxSanitizeCb::class)[0];
    $sanitizeCb = $sanitizeAttr->newInstance();
    expect($sanitizeCb)->toBeInstanceOf(MetaBoxSanitizeCb::class);
});

it('supports multiple callback attributes on different methods', function () {
    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);
    $callbackMethods = [];

    foreach ($reflection->getMethods() as $method) {
        $callbackAttrs = array_merge(
            $method->getAttributes(MetaBoxCb::class),
            $method->getAttributes(UpdateCountCallback::class),
            $method->getAttributes(MetaBoxSanitizeCb::class)
        );

        if (! empty($callbackAttrs)) {
            $callbackMethods[$method->getName()] = $callbackAttrs[0]->newInstance();
        }
    }

    expect($callbackMethods)->toHaveCount(3);
    expect($callbackMethods)->toHaveKeys(['customMetaBox', 'updateTermCount', 'sanitizeTerms']);

    // Verify each callback has correct type
    expect($callbackMethods['customMetaBox'])->toBeInstanceOf(MetaBoxCb::class);
    expect($callbackMethods['updateTermCount'])->toBeInstanceOf(UpdateCountCallback::class);
    expect($callbackMethods['sanitizeTerms'])->toBeInstanceOf(MetaBoxSanitizeCb::class);
});

it('demonstrates attribute composition pattern for taxonomies', function () {
    // This test demonstrates how the taxonomy attribute system works:
    // 1. Taxonomy attribute defines the main taxonomy
    // 2. Sub-attributes (Hierarchical, PublicTaxonomy, ShowUI, etc.) configure taxonomy settings
    // 3. Method-level attributes (MetaBoxCb, UpdateCountCallback, etc.) define callbacks

    $reflection = new ReflectionClass(ProductCategoryWithSubAttributes::class);

    // Main taxonomy definition
    $taxonomyAttr = $reflection->getAttributes(Taxonomy::class)[0]->newInstance();
    expect($taxonomyAttr->slug)->toBe('product-category');

    // Test attribute instantiation
    $hierarchicalAttr = $reflection->getAttributes(Hierarchical::class)[0]->newInstance();
    expect($hierarchicalAttr)->toBeInstanceOf(Hierarchical::class);

    $publicAttr = $reflection->getAttributes(PublicTaxonomy::class)[0]->newInstance();
    expect($publicAttr)->toBeInstanceOf(PublicTaxonomy::class);

    $showUIAttr = $reflection->getAttributes(ShowUI::class)[0]->newInstance();
    expect($showUIAttr)->toBeInstanceOf(ShowUI::class);

    $showInRestAttr = $reflection->getAttributes(ShowInRest::class)[0]->newInstance();
    expect($showInRestAttr)->toBeInstanceOf(ShowInRest::class);

    $objectTypeAttr = $reflection->getAttributes(ObjectType::class)[0]->newInstance();
    expect($objectTypeAttr)->toBeInstanceOf(ObjectType::class);

    // Callback methods configuration (method-level)
    $callbackMethods = [];
    foreach ($reflection->getMethods() as $method) {
        $callbackAttrs = array_merge(
            $method->getAttributes(MetaBoxCb::class),
            $method->getAttributes(UpdateCountCallback::class),
            $method->getAttributes(MetaBoxSanitizeCb::class)
        );

        if (! empty($callbackAttrs)) {
            $callbackMethods[] = [
                'method' => $method->getName(),
                'attribute' => $callbackAttrs[0]->newInstance(),
            ];
        }
    }

    expect($callbackMethods)->toHaveCount(3);

    // Verify the composition creates a complete taxonomy configuration
    $allClassAttributes = $reflection->getAttributes();
    $allMethodAttributes = [];
    foreach ($reflection->getMethods() as $method) {
        $allMethodAttributes = array_merge($allMethodAttributes, $method->getAttributes());
    }

    // Should have class-level attributes for configuration
    expect($allClassAttributes)->toHaveCount(6); // Taxonomy + 5 sub-attributes

    // Should have method-level attributes for callbacks
    expect($allMethodAttributes)->toHaveCount(3); // 3 callback attributes
});

it('attributes have correct target configurations', function () {
    // Verify class-level attributes target classes
    $classLevelAttributes = [
        Taxonomy::class,
        Hierarchical::class,
        PublicTaxonomy::class,
        ShowUI::class,
        ShowInRest::class,
        ObjectType::class,
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
        MetaBoxCb::class,
        UpdateCountCallback::class,
        MetaBoxSanitizeCb::class,
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
    // Validation will be handled by TaxonomyDiscovery

    expect(fn () => new Taxonomy('', '', '', []))->not->toThrow(\Throwable::class);
    expect(fn () => new Hierarchical(false))->not->toThrow(\Throwable::class);
    expect(fn () => new PublicTaxonomy(false))->not->toThrow(\Throwable::class);
    expect(fn () => new ShowUI(false))->not->toThrow(\Throwable::class);
    expect(fn () => new ShowInRest(false))->not->toThrow(\Throwable::class);
    expect(fn () => new ObjectType([]))->not->toThrow(\Throwable::class);
    expect(fn () => new MetaBoxCb)->not->toThrow(\Throwable::class);
    expect(fn () => new UpdateCountCallback)->not->toThrow(\Throwable::class);
    expect(fn () => new MetaBoxSanitizeCb)->not->toThrow(\Throwable::class);

    // Even completely invalid values should not throw
    expect(fn () => new Taxonomy('invalid slug with spaces', 'inv@lid', 'pl{ur}al', 'invalid'))->not->toThrow(\Throwable::class);
    expect(fn () => new Hierarchical('not-boolean'))->not->toThrow(\Throwable::class); // Wrong type
    expect(fn () => new ObjectType('not-array'))->not->toThrow(\Throwable::class); // Wrong type
});
