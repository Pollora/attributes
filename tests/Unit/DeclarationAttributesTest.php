<?php

declare(strict_types=1);

use Pollora\Abilities\Domain\Model\Behaviour;
use Pollora\Ajax\Domain\Model\AjaxAccess;
use Pollora\Attributes\Ability;
use Pollora\Attributes\Ajax;
use Pollora\Attributes\Async;
use Pollora\Attributes\BlockBinding;
use Pollora\Attributes\BlockBinding\BindingField;
use Pollora\Attributes\CapabilitySet;
use Pollora\Attributes\Meta;
use Pollora\Attributes\ModifyRole;
use Pollora\Attributes\PostMeta;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\Role;
use Pollora\Attributes\Role\Grants;
use Pollora\Attributes\Role\GrantsPostType;
use Pollora\Attributes\Role\GrantsTaxonomy;
use Pollora\Attributes\Role\Without;
use Pollora\Attributes\Taxonomy;
use Pollora\Attributes\TermMeta;
use Pollora\Attributes\WpCli;
use Pollora\Attributes\WpCli\AfterInvoke;
use Pollora\Attributes\WpCli\BeforeInvoke;
use Pollora\Attributes\WpCli\IsDeferred;
use Pollora\Attributes\WpCli\Synopsis;
use Pollora\Attributes\WpCli\When;
use Pollora\Attributes\WpRestRoute;
use Pollora\BlockBinding\Domain\Enums\BindingFieldType;
use Pollora\Meta\Domain\Enums\Control;
use Pollora\Role\Domain\Enums\Access;

enum DeclarationTestCapability: string
{
    case Moderate = 'moderate_things';
    case Audit = 'audit_things';
}

#[Ability('plugin/search', 'Searches the site', 'content', label: 'Search', behaviour: Behaviour::Reads)]
final class DeclaredAbility {}

final class DeclaredAjax
{
    #[Ajax('save_note')]
    public function save(): void {}
}

it('declares an ability with its name, description, category, label and behaviour', function (): void {
    $ability = (new ReflectionClass(DeclaredAbility::class))->getAttributes(Ability::class)[0]->newInstance();

    expect($ability->name)->toBe('plugin/search')
        ->and($ability->description)->toBe('Searches the site')
        ->and($ability->category)->toBe('content')
        ->and($ability->label)->toBe('Search')
        ->and($ability->behaviour)->toBe(Behaviour::Reads);
});

it('defaults an ability to an empty label and a read-only behaviour', function (): void {
    $ability = new Ability('a/b', 'd', 'c');

    expect($ability->label)->toBe('')
        ->and($ability->behaviour)->toBe(Behaviour::Reads);
});

it('declares an AJAX action on a method, open to logged-in users by default', function (): void {
    $method = new ReflectionMethod(DeclaredAjax::class, 'save');
    $ajax = $method->getAttributes(Ajax::class)[0]->newInstance();

    expect($ajax->action)->toBe('save_note')
        ->and($ajax->access)->toBe(AjaxAccess::LOGGED);
});

it('keeps an explicit AJAX access', function (): void {
    expect((new Ajax('ping', AjaxAccess::ALL))->access)->toBe(AjaxAccess::ALL);
});

it('leaves every Async option unset until the declaration sets it', function (): void {
    $async = new Async;

    expect($async->delay)->toBeNull()
        ->and($async->via)->toBeNull()
        ->and($async->onQueue)->toBeNull()
        ->and($async->unique)->toBeFalse()
        ->and($async->tries)->toBeNull()
        ->and($async->backoff)->toBeNull()
        ->and($async->asUser)->toBeNull()
        ->and($async->capture)->toBeNull()
        ->and($async->when)->toBeNull()
        ->and($async->keepMissing)->toBeFalse()
        ->and($async->except)->toBe([]);
});

it('carries the Async options it is given', function (): void {
    $delay = new DateInterval('PT5M');
    $async = new Async(delay: $delay, via: 'redis', onQueue: 'mail', unique: 60, tries: 3, backoff: [1, 5], asUser: true, capture: 'user', when: 'isReady', keepMissing: true, except: ['a', 'b']);

    expect($async->delay)->toBe($delay)
        ->and($async->via)->toBe('redis')
        ->and($async->onQueue)->toBe('mail')
        ->and($async->unique)->toBe(60)
        ->and($async->tries)->toBe(3)
        ->and($async->backoff)->toBe([1, 5])
        ->and($async->asUser)->toBeTrue()
        ->and($async->capture)->toBe('user')
        ->and($async->when)->toBe('isReady')
        ->and($async->keepMissing)->toBeTrue()
        ->and($async->except)->toBe(['a', 'b']);
});

it('declares a block binding source with the default context and no post type restriction', function (): void {
    $binding = new BlockBinding('theme/price');

    expect($binding->name)->toBe('theme/price')
        ->and($binding->label)->toBeNull()
        ->and($binding->usesContext)->toBe(['postId', 'postType'])
        ->and($binding->postTypes)->toBe([]);
});

it('wraps a single post type of a block binding in a list and re-indexes the context', function (): void {
    $binding = new BlockBinding('theme/price', 'Price', usesContext: [3 => 'postId', 7 => 'queryId'], postTypes: 'product');

    expect($binding->label)->toBe('Price')
        ->and($binding->usesContext)->toBe(['postId', 'queryId'])
        ->and($binding->postTypes)->toBe(['product']);
});

it('keeps a list of post types for a block binding', function (): void {
    expect((new BlockBinding('x/y', postTypes: ['post', 'page']))->postTypes)->toBe(['post', 'page']);
});

it('declares a binding field as text unless told otherwise', function (): void {
    $field = new BindingField;

    expect($field->name)->toBeNull()
        ->and($field->label)->toBeNull()
        ->and($field->type)->toBe(BindingFieldType::Text);
});

it('accepts an enum or a string as the type of a binding field', function (): void {
    expect((new BindingField('cover', 'Cover', BindingFieldType::Image))->type)->toBe(BindingFieldType::Image)
        ->and((new BindingField(type: 'custom'))->type)->toBe('custom');
});

it('tells which binding field types hold a URL', function (): void {
    expect(BindingFieldType::Text->isUrl())->toBeFalse()
        ->and(BindingFieldType::Url->isUrl())->toBeTrue()
        ->and(BindingFieldType::Image->isUrl())->toBeTrue();
});

it('declares a capability set with an optional label', function (): void {
    expect((new CapabilitySet)->label)->toBeNull()
        ->and((new CapabilitySet('Moderation'))->label)->toBe('Moderation');
});

it('resolves a meta key to the explicit key, or to the snake-cased property name', function (): void {
    expect((new Meta(key: 'custom_key'))->resolveKey('anyProperty'))->toBe('custom_key')
        ->and((new Meta)->resolveKey('releaseDate'))->toBe('release_date');
});

it('keeps private, single and non-REST as the defaults of a meta field', function (): void {
    $meta = new Meta;

    expect($meta->showInRest)->toBeFalse()
        ->and($meta->single)->toBeTrue()
        ->and($meta->revisions)->toBeFalse()
        ->and($meta->media)->toBeFalse()
        ->and($meta->public)->toBeFalse()
        ->and($meta->rules)->toBe([])
        ->and($meta->hints)->toBe([])
        ->and($meta->control)->toBeNull()
        ->and($meta->sanitize)->toBeNull()
        ->and($meta->capability)->toBeNull();
});

it('carries the options of a meta field', function (): void {
    $meta = new Meta(showInRest: true, label: 'L', description: 'D', sanitize: 'absint', capability: 'edit_posts', revisions: true, rules: ['required'], single: false, items: 'string', control: Control::Number, group: 'g', hints: ['min' => 1], media: true, public: true);

    expect($meta->showInRest)->toBeTrue()
        ->and($meta->label)->toBe('L')
        ->and($meta->description)->toBe('D')
        ->and($meta->sanitize)->toBe('absint')
        ->and($meta->capability)->toBe('edit_posts')
        ->and($meta->revisions)->toBeTrue()
        ->and($meta->rules)->toBe(['required'])
        ->and($meta->single)->toBeFalse()
        ->and($meta->items)->toBe('string')
        ->and($meta->control)->toBe(Control::Number)
        ->and($meta->group)->toBe('g')
        ->and($meta->hints)->toBe(['min' => 1])
        ->and($meta->media)->toBeTrue()
        ->and($meta->public)->toBeTrue();
});

it('wraps the single object type of a meta group in a list, and keeps a list', function (string $attribute, string $property): void {
    expect((new $attribute('one'))->$property)->toBe(['one'])
        ->and((new $attribute([4 => 'a', 9 => 'b']))->$property)->toBe(['a', 'b']);
})->with([
    'PostMeta' => [PostMeta::class, 'postTypes'],
    'TermMeta' => [TermMeta::class, 'taxonomies'],
]);

it('declares a role with a slug and the default options', function (): void {
    $role = new Role('shop_manager');

    expect($role->slug)->toBe('shop_manager')
        ->and($role->label)->toBeNull()
        ->and($role->inherits)->toBeNull()
        ->and($role->allowSensitive)->toBeFalse()
        ->and($role->textDomain)->toBeNull();
});

it('carries the options of a role and of a role modification', function (): void {
    $role = new Role('editor_plus', 'Editor+', 'editor', true, 'my-domain');
    $modify = new ModifyRole('editor', true);

    expect($role->label)->toBe('Editor+')
        ->and($role->inherits)->toBe('editor')
        ->and($role->allowSensitive)->toBeTrue()
        ->and($role->textDomain)->toBe('my-domain')
        ->and($modify->slug)->toBe('editor')
        ->and($modify->allowSensitive)->toBeTrue()
        ->and((new ModifyRole('author'))->allowSensitive)->toBeFalse();
});

it('collects the capabilities a role grants or removes, as strings or backed enums', function (string $attribute): void {
    $attributeInstance = new $attribute('edit_posts', DeclarationTestCapability::Moderate);

    expect($attributeInstance->capabilities)->toBe(['edit_posts', DeclarationTestCapability::Moderate])
        ->and((new $attribute)->capabilities)->toBe([]);
})->with([Grants::class, Without::class]);

it('grants access to a post type at the editor level by default', function (): void {
    expect((new GrantsPostType('book'))->postType)->toBe('book')
        ->and((new GrantsPostType('book'))->access)->toBe(Access::Editor)
        ->and((new GrantsPostType('book', Access::Author))->access)->toBe(Access::Author)
        ->and((new GrantsTaxonomy('genre'))->taxonomy)->toBe('genre');
});

it('builds the generic capabilities of each access level on the one below', function (): void {
    $contributor = ['edit_posts', 'delete_posts'];
    $author = [...$contributor, 'publish_posts', 'edit_published_posts', 'delete_published_posts'];

    expect(Access::Contributor->genericCapabilities())->toBe($contributor)
        ->and(Access::Author->genericCapabilities())->toBe($author)
        ->and(Access::Editor->genericCapabilities())->toBe([...$author, 'edit_others_posts', 'delete_others_posts', 'read_private_posts', 'edit_private_posts', 'delete_private_posts']);
});

it('resolves a taxonomy slug to the explicit slug, or to the kebab-cased class name cut to 20 characters', function (): void {
    expect((new Taxonomy('genre'))->resolveSlug('App\\Taxonomies\\Anything'))->toBe('genre')
        ->and((new Taxonomy)->resolveSlug('App\\Taxonomies\\ProductType'))->toBe('product-type')
        ->and((new Taxonomy)->resolveSlug('App\\Taxonomies\\AVeryLongTaxonomyClassNameIndeed'))->toHaveLength(20);
});

it('resolves a WP-CLI command name to the explicit name, or to the class name without its Command suffix', function (): void {
    expect((new WpCli('custom name'))->getCommandName('App\\Cli\\Whatever'))->toBe('custom name')
        ->and((new WpCli)->getCommandName('App\\Cli\\ImportUsersCommand'))->toBe((new WpCli)->getCommandName('App\\Cli\\ImportUsers'))
        ->and((new WpCli)->commandName)->toBeNull();
});

it('carries the settings of the WP-CLI sub-attributes', function (): void {
    expect((new AfterInvoke('flush'))->callback)->toBe('flush')
        ->and((new AfterInvoke(['Cls', 'run']))->callback)->toBe(['Cls', 'run'])
        ->and((new BeforeInvoke('prepare'))->callback)->toBe('prepare')
        ->and((new BeforeInvoke(['Cls', 'run']))->callback)->toBe(['Cls', 'run'])
        ->and((new IsDeferred)->deferred)->toBeTrue()
        ->and((new IsDeferred(false))->deferred)->toBeFalse()
        ->and((new Synopsis('<id>'))->synopsis)->toBe('<id>')
        ->and((new Synopsis([['type' => 'positional']]))->synopsis)->toBe([['type' => 'positional']])
        ->and((new When('after_wp_load'))->hook)->toBe('after_wp_load');
});

it('declares a REST route with no permission callback unless given one', function (): void {
    $route = new WpRestRoute('app/v1', '/books');

    expect($route->namespace)->toBe('app/v1')
        ->and($route->route)->toBe('/books')
        ->and($route->permissionCallback)->toBeNull()
        ->and((new WpRestRoute('app/v1', '/x', 'my_check'))->permissionCallback)->toBe('my_check');
});

it('carries every optional setting of an admin column into its registration arguments', function (): void {
    $callback = static fn (): string => 'cell';

    $arguments = (new AdminCol(
        'rating', 'Rating', 'meta_value_num', 'star-filled', 'Y-m-d', 'edit', 'edit_posts', 'read_post', 'DESC',
        'rating_key', 'genre', 'thumbnail', 'post_date', 120,
    ))->configureArguments([], $callback);

    expect($arguments['admin_cols']['rating'])->toBe([
        'title' => 'Rating',
        'function' => $callback,
        'title_icon' => 'star-filled',
        'date_format' => 'Y-m-d',
        'link' => 'edit',
        'cap' => 'edit_posts',
        'post_cap' => 'read_post',
        'default' => 'DESC',
        'meta_key' => 'rating_key',
        'taxonomy' => 'genre',
        'featured_image' => 'thumbnail',
        'post_field' => 'post_date',
        'width' => 120,
        'sortable' => 'meta_value_num',
    ]);
});
