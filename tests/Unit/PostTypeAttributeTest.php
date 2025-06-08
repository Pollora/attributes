<?php

declare(strict_types=1);

use Pollora\Attributes\AttributeProcessor;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\PostType\AdminCols;
use Pollora\Attributes\PostType\AdminFilters;
use Pollora\Attributes\PostType\Archive;
use Pollora\Attributes\PostType\BlockEditor;
use Pollora\Attributes\PostType\CanExport;
use Pollora\Attributes\PostType\Capabilities;
use Pollora\Attributes\PostType\CapabilityType;
use Pollora\Attributes\PostType\Chronological;
use Pollora\Attributes\PostType\DashboardActivity;
use Pollora\Attributes\PostType\DashboardGlance;
use Pollora\Attributes\PostType\DeleteWithUser;
use Pollora\Attributes\PostType\Description;
use Pollora\Attributes\PostType\ExcludeFromSearch;
use Pollora\Attributes\PostType\FeaturedImage;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\Hierarchical;
use Pollora\Attributes\PostType\Label;
use Pollora\Attributes\PostType\MapMetaCap;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\MenuPosition;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\PublicPostType;
use Pollora\Attributes\PostType\QueryVar;
use Pollora\Attributes\PostType\QuickEdit;
use Pollora\Attributes\PostType\RegisterMetaBoxCb;
use Pollora\Attributes\PostType\RestBase;
use Pollora\Attributes\PostType\RestControllerClass;
use Pollora\Attributes\PostType\RestNamespace;
use Pollora\Attributes\PostType\Rewrite;
use Pollora\Attributes\PostType\ShowInAdminBar;
use Pollora\Attributes\PostType\ShowInFeed;
use Pollora\Attributes\PostType\ShowInMenu;
use Pollora\Attributes\PostType\ShowInNavMenus;
use Pollora\Attributes\PostType\ShowInRest;
use Pollora\Attributes\PostType\ShowUI;
use Pollora\Attributes\PostType\SiteFilters;
use Pollora\Attributes\PostType\SiteSortables;
use Pollora\Attributes\PostType\Supports;
use Pollora\Attributes\PostType\Taxonomies;
use Pollora\Attributes\PostType\Template;
use Pollora\Attributes\PostType\TemplateLock;
use Pollora\Attributes\PostType\TitlePlaceholder;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

// Test class that implements the PostType interface
#[PubliclyQueryable]
#[HasArchive]
#[Supports(['title', 'editor', 'thumbnail'])]
#[MenuIcon('dashicons-calendar')]
#[ExcludeFromSearch]
#[Hierarchical]
#[ShowInAdminBar]
#[MenuPosition(5)]
#[CapabilityType('page')]
#[MapMetaCap]
#[CanExport]
#[DeleteWithUser]
#[ShowInRest]
#[TitlePlaceholder('Enter event title here')]
#[RestBase('events')]
#[BlockEditor]
#[DashboardActivity]
#[QuickEdit]
#[ShowInFeed]
#[PublicPostType]
#[Label('Test Post Types')]
#[Description('This is a test post type description')]
#[ShowUI]
#[ShowInMenu]
#[ShowInNavMenus]
#[QueryVar('test_query_var')]
#[Rewrite(['slug' => 'test-slug'])]
#[RestNamespace('test/v1')]
#[RestControllerClass('WP_REST_Posts_Controller')]
#[Capabilities(['create_posts' => 'create_test'])]
#[Taxonomies(['category', 'post_tag'])]
#[Template([['core/paragraph', ['placeholder' => 'Add content here...']]])]
#[TemplateLock('all')]
#[Archive(['nopaging' => true])]
#[AdminFilters(['date', 'author'])]
#[FeaturedImage('Featured Image Label')]
#[SiteFilters(['date', 'author'])]
#[SiteSortables(['title' => 'Title', 'date' => 'Date'])]
#[DashboardGlance]
#[AdminCols(['title' => ['title' => 'Title'], 'date' => ['title' => 'Date']])]
#[Chronological]
class TestPostType implements PostTypeAttributeInterface
{
    public array $attributeArgs = [];

    protected string $slug = 'test-post-type';

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return 'Test Post Type';
    }

    public function getPluralName(): string
    {
        return 'Test Post Types';
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Test Post Types',
            'singular_name' => 'Test Post Type',
        ];
    }

    #[AdminCol('title', 'Event Title')]
    public function formatTitle($postId): string
    {
        return "Title for post {$postId}";
    }

    #[AdminCol('featured_image', 'Image')]
    public function formatImage($postId): string
    {
        return "Image for post {$postId}";
    }

    #[RegisterMetaBoxCb]
    public function registerMetaBoxCallback($post): void
    {
        // Register meta box implementation
    }

    public function withArgs(): array
    {
        return [
            'can_export' => true,
        ];
    }

    public function getArgs(): array
    {
        return array_merge(
            $this->attributeArgs,
            $this->withArgs(),
            [
                'labels' => $this->getLabels(),
            ]
        );
    }
}

test('PubliclyQueryable attribute sets publicly_queryable parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['publicly_queryable'])->toBeTrue();
});

test('HasArchive attribute sets has_archive parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['has_archive'])->toBeTrue();
});

test('Supports attribute sets supports parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['supports'])
        ->toBe(['title', 'editor', 'thumbnail'])
        ->toBeArray()
        ->toHaveCount(3);
});

test('MenuIcon attribute sets menu_icon parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['menu_icon'])->toBe('dashicons-calendar');
});

test('ExcludeFromSearch attribute sets exclude_from_search parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['exclude_from_search'])->toBeTrue();
});

test('Hierarchical attribute sets hierarchical parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['hierarchical'])->toBeTrue();
});

test('ShowInAdminBar attribute sets show_in_admin_bar parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_admin_bar'])->toBeTrue();
});

test('MenuPosition attribute sets menu_position parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['menu_position'])->toBe(5);
});

test('CapabilityType attribute sets capability_type parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['capability_type'])->toBe('page');
});

test('MapMetaCap attribute sets map_meta_cap parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['map_meta_cap'])->toBeTrue();
});

test('CanExport attribute sets can_export parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['can_export'])->toBeTrue();
});

test('DeleteWithUser attribute sets delete_with_user parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['delete_with_user'])->toBeTrue();
});

test('ShowInRest attribute sets show_in_rest parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_rest'])->toBeTrue();
});

test('TitlePlaceholder attribute sets title_placeholder parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['title_placeholder'])->toBe('Enter event title here');
});

test('RestBase attribute sets rest_base parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['rest_base'])->toBe('events');
});

test('BlockEditor attribute sets block_editor parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_rest'])->toBeTrue();
});

test('DashboardActivity attribute sets dashboard_activity parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['dashboard_activity'])->toBeTrue();
});

test('QuickEdit attribute sets quick_edit parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['quick_edit'])->toBeTrue();
});

test('ShowInFeed attribute sets show_in_feed parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_feed'])->toBeTrue();
});

test('getArgs method merges attribute args with withArgs and labels', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    $args = $postType->getArgs();

    expect($args)->toBeArray()
        ->toHaveKey('publicly_queryable')
        ->toHaveKey('has_archive')
        ->toHaveKey('supports')
        ->toHaveKey('menu_icon')
        ->toHaveKey('exclude_from_search')
        ->toHaveKey('hierarchical')
        ->toHaveKey('show_in_admin_bar')
        ->toHaveKey('menu_position')
        ->toHaveKey('capability_type')
        ->toHaveKey('map_meta_cap')
        ->toHaveKey('can_export')
        ->toHaveKey('delete_with_user')
        ->toHaveKey('show_in_rest')
        ->toHaveKey('title_placeholder')
        ->toHaveKey('rest_base')
        ->toHaveKey('dashboard_activity')
        ->toHaveKey('quick_edit')
        ->toHaveKey('show_in_feed')
        ->toHaveKey('public')
        ->toHaveKey('site_sortables')
        ->toHaveKey('site_filters')
        ->toHaveKey('featured_image')
        ->toHaveKey('archive')
        ->toHaveKey('admin_filters')
        ->toHaveKey('template_lock')
        ->toHaveKey('template')
        ->toHaveKey('taxonomies')
        ->toHaveKey('register_meta_box_cb')
        ->toHaveKey('capabilities')
        ->toHaveKey('rest_controller_class')
        ->toHaveKey('rest_namespace')
        ->toHaveKey('rewrite')
        ->toHaveKey('query_var')
        ->toHaveKey('show_in_nav_menus')
        ->toHaveKey('show_in_menu')
        ->toHaveKey('show_ui')
        ->toHaveKey('label')
        ->toHaveKey('description')
        ->toHaveKey('dashboard_glance')
        ->toHaveKey('admin_cols')
        ->toHaveKey('orderby')
        ->toHaveKey('order')
        ->toHaveKey('labels')
        ->and($args['can_export'])->toBeTrue()
        ->and($args['labels'])->toBeArray()
        ->toHaveKey('name')
        ->toHaveKey('singular_name');

});

test('PublicPostType attribute sets public parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['public'])->toBeTrue();
});

test('SiteSortables attribute sets site_sortables parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['site_sortables'])->toBe(['title' => 'Title', 'date' => 'Date']);
});

test('SiteFilters attribute sets site_filters parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['site_filters'])->toBe(['date', 'author']);
});

test('FeaturedImage attribute sets featured_image parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['featured_image'])->toBe('Featured Image Label');
});

test('Archive attribute sets archive parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['archive'])->toBe(['nopaging' => true]);
});

test('AdminFilters attribute sets admin_filters parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['admin_filters'])->toBe(['date', 'author']);
});

test('TemplateLock attribute sets template_lock parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['template_lock'])->toBe('all');
});

test('Template attribute sets template parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['template'])->toBe([['core/paragraph', ['placeholder' => 'Add content here...']]]);
});

test('Taxonomies attribute sets taxonomies parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['taxonomies'])->toBe(['category', 'post_tag']);
});

test('RegisterMetaBoxCb attribute sets register_meta_box_cb parameter', function () {
    $postType = new TestPostType;

    // Reset attributeArgs to avoid interference
    $postType->attributeArgs = [];

    // Get methods with RegisterMetaBoxCb attribute
    $reflectionClass = new ReflectionClass($postType);
    $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

    foreach ($methods as $method) {
        $attributes = $method->getAttributes(RegisterMetaBoxCb::class);
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            $attributeInstance->handle(null, $postType, $method, $attributeInstance);
        }
    }

    expect($postType->attributeArgs['register_meta_box_cb'])->toBeArray()
        ->toHaveCount(2)
        ->toHaveKey(0)
        ->toHaveKey(1)
        ->and($postType->attributeArgs['register_meta_box_cb'][0])->toBe($postType)
        ->and($postType->attributeArgs['register_meta_box_cb'][1])->toBe('registerMetaBoxCallback');

});

test('Capabilities attribute sets capabilities parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['capabilities'])->toBe(['create_posts' => 'create_test']);
});

test('RestControllerClass attribute sets rest_controller_class parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['rest_controller_class'])->toBe('WP_REST_Posts_Controller');
});

test('RestNamespace attribute sets rest_namespace parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['rest_namespace'])->toBe('test/v1');
});

test('Rewrite attribute sets rewrite parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['rewrite'])->toBe(['slug' => 'test-slug']);
});

test('QueryVar attribute sets query_var parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['query_var'])->toBe('test_query_var');
});

test('ShowInNavMenus attribute sets show_in_nav_menus parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_nav_menus'])->toBeTrue();
});

test('ShowInMenu attribute sets show_in_menu parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_in_menu'])->toBeTrue();
});

test('ShowUI attribute sets show_ui parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['show_ui'])->toBeTrue();
});

test('Label attribute sets label parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['label'])->toBe('Test Post Types');
});

test('Description attribute sets description parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['description'])->toBe('This is a test post type description');
});

test('DashboardGlance attribute sets dashboard_glance parameter', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['dashboard_glance'])->toBeTrue();
});

test('AdminCol attribute adds columns to admin_cols parameter', function () {
    $postType = new TestPostType;

    // Reset admin_cols to avoid interference
    $postType->attributeArgs = [];

    // Get methods with AdminCol attribute
    $reflectionClass = new ReflectionClass($postType);
    $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

    foreach ($methods as $method) {
        $attributes = $method->getAttributes(AdminCol::class);
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            $attributeInstance->handle(null, $postType, $method, $attributeInstance);
        }
    }

    expect($postType->attributeArgs['admin_cols'])->toBeArray()
        ->toHaveKey('title')
        ->toHaveKey('featured_image')
        ->and($postType->attributeArgs['admin_cols']['title'])->toBeArray()
        ->toHaveKey('title')
        ->toHaveKey('function')
        ->and($postType->attributeArgs['admin_cols']['title']['title'])->toBe('Event Title')
        ->and($postType->attributeArgs['admin_cols']['title']['function'])->toBeArray()
        ->toHaveCount(2)
        ->toContain($postType)
        ->and($postType->attributeArgs['admin_cols']['featured_image'])->toBeArray()
        ->toHaveKey('title')
        ->toHaveKey('function')
        ->not->toHaveKey('width')
        ->and($postType->attributeArgs['admin_cols']['featured_image']['title'])->toBe('Image');

});

test('AdminCols attribute sets admin_cols parameter', function () {
    $postType = new TestPostType;

    // Reset admin_cols to avoid interference
    $postType->attributeArgs = [];

    // Create a test class that extends AdminCols to access the protected method
    $adminCols = new class(['title' => ['title' => 'Title'], 'date' => ['title' => 'Date']]) extends AdminCols
    {
        public function publicConfigure(PostTypeAttributeInterface $postType): void
        {
            $this->configure($postType);
        }
    };

    // Apply the AdminCols attribute
    $adminCols->publicConfigure($postType);

    expect($postType->attributeArgs['admin_cols'])->toBe(['title' => ['title' => 'Title'], 'date' => ['title' => 'Date']]);
});

test('Chronological attribute sets orderby and order parameters', function () {
    $postType = new TestPostType;
    $processor = new AttributeProcessor;
    $processor->process($postType);

    expect($postType->attributeArgs['orderby'])->toBe('date')
        ->and($postType->attributeArgs['order'])->toBe('DESC');
});
