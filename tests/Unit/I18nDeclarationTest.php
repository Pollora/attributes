<?php

declare(strict_types=1);

/**
 * `resources/i18n.php` tells a translation tool which attribute parameters hold
 * text shown to a person. It is data, read without this package's code, so
 * nothing but this test keeps it in step with the attributes: every text
 * parameter of every attribute is classed here, as translatable (in the file)
 * or technical (in the list below).
 */

/**
 * Text parameters that are not shown to anyone: slugs, keys, hooks,
 * capabilities, formats, icons, class names, callbacks, raw registration
 * arguments. Keyed by the attribute, relative to `Pollora\Attributes\`.
 *
 * @var array<string, list<string>>
 */
const TECHNICAL_PARAMETERS = [
    'Async' => ['via', 'onQueue', 'backoff', 'capture', 'when', 'except'],
    'BlockBinding' => ['name', 'usesContext', 'postTypes'],
    'BlockBinding\BindingField' => ['name', 'type'],
    'Action' => ['hook'],
    'Filter' => ['hook'],
    'Ability' => ['name', 'category'],
    'Ajax' => ['action'],
    'Meta' => ['key', 'sanitize', 'capability', 'rules', 'items', 'hints'],
    'ModifyRole' => ['slug'],
    'PostMeta' => ['postTypes'],
    'PostType' => ['slug', 'textDomain'],
    'PostType\AdminCol' => ['key', 'sortable', 'titleIcon', 'dateFormat', 'link', 'cap', 'postCap', 'default', 'metaKey', 'taxonomy', 'featuredImage', 'postField'],
    'PostType\AdminCols' => ['value'],
    'PostType\AdminFilters' => ['value'],
    'PostType\Archive' => ['value'],
    'PostType\Capabilities' => ['value'],
    'PostType\CapabilityType' => ['value'],
    'PostType\HasArchive' => ['value'],
    'PostType\MenuIcon' => ['value'],
    'PostType\QueryVar' => ['value'],
    'PostType\RestBase' => ['value'],
    'PostType\RestControllerClass' => ['value'],
    'PostType\RestNamespace' => ['value'],
    'PostType\Rewrite' => ['value'],
    'PostType\ShowInMenu' => ['value'],
    'PostType\SiteFilters' => ['value'],
    'PostType\SiteSortables' => ['value'],
    'PostType\Supports' => ['features'],
    'PostType\Taxonomies' => ['value'],
    'PostType\Template' => ['value'],
    'PostType\TemplateLock' => ['value'],
    'Role' => ['slug', 'inherits', 'textDomain'],
    'Role\Grants' => ['capabilities'],
    'Role\GrantsPostType' => ['postType'],
    'Role\GrantsTaxonomy' => ['taxonomy'],
    'Role\Without' => ['capabilities'],
    'Schedule' => ['recurrence', 'hook', 'args'],
    'Taxonomy' => ['slug', 'objectType', 'textDomain'],
    'Taxonomy\Args' => ['value'],
    'Taxonomy\Capabilities' => ['value'],
    'Taxonomy\DefaultTerm' => ['value'],
    'Taxonomy\ObjectType' => ['value'],
    'Taxonomy\QueryVar' => ['value'],
    'Taxonomy\RestBase' => ['value'],
    'Taxonomy\RestControllerClass' => ['value'],
    'Taxonomy\RestNamespace' => ['value'],
    'Taxonomy\Rewrite' => ['value'],
    'Taxonomy\ShowInMenu' => ['value'],
    'TermMeta' => ['taxonomies'],
    'WpCli' => ['commandName'],
    'WpCli\AfterInvoke' => ['callback'],
    'WpCli\BeforeInvoke' => ['callback'],
    'WpCli\Command' => ['commandName'],
    'WpCli\Synopsis' => ['synopsis'],
    'WpCli\When' => ['hook'],
    'WpRestRoute' => ['namespace', 'route', 'permissionCallback'],
    'WpRestRoute\Method' => ['methods', 'permissionCallback'],
];

/**
 * The parameters that can hold text, by attribute class: the ones whose type
 * accepts a string or an array (an attribute that inherits its constructor,
 * such as `Action`, is classed as itself).
 *
 * @return array<class-string, list<string>>
 */
function textParametersOfAttributes(): array
{
    $root = dirname(__DIR__, 2).'/src';
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    $found = [];

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $relative = substr($file->getPathname(), strlen($root) + 1, -4);
        $class = 'Pollora\Attributes\\'.str_replace('/', '\\', $relative);

        if (! class_exists($class) || (new ReflectionClass($class))->getAttributes(Attribute::class) === []) {
            continue;
        }

        $constructor = (new ReflectionClass($class))->getConstructor();

        if ($constructor === null) {
            continue;
        }

        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

            foreach ($types as $member) {
                if ($member instanceof ReflectionNamedType && in_array($member->getName(), ['string', 'array'], true)) {
                    $parameters[] = $parameter->getName();

                    break;
                }
            }
        }

        if ($parameters !== []) {
            $found[$class] = $parameters;
        }
    }

    ksort($found);

    return $found;
}

/**
 * @return array<class-string, list<string>>
 */
function declaredTranslatableFields(): array
{
    return require dirname(__DIR__, 2).'/resources/i18n.php';
}

/**
 * @return array<class-string, list<string>>
 */
function declaredTechnicalParameters(): array
{
    $technical = [];

    foreach (TECHNICAL_PARAMETERS as $class => $parameters) {
        $technical['Pollora\Attributes\\'.$class] = $parameters;
    }

    return $technical;
}

it('finds the attributes to classify', function (): void {
    expect(array_keys(textParametersOfAttributes()))
        ->toContain('Pollora\Attributes\PostType', 'Pollora\Attributes\Taxonomy\Labels', 'Pollora\Attributes\Ability');
});

it('classes every text parameter of every attribute', function (): void {
    $translatable = declaredTranslatableFields();
    $technical = declaredTechnicalParameters();
    $unclassed = [];

    foreach (textParametersOfAttributes() as $class => $parameters) {
        foreach ($parameters as $parameter) {
            if (! in_array($parameter, $translatable[$class] ?? [], true) && ! in_array($parameter, $technical[$class] ?? [], true)) {
                $unclassed[] = $class.'::$'.$parameter;
            }
        }
    }

    expect($unclassed)->toBe([], sprintf(
        "These attribute parameters can hold text and are not classed:\n  %s\nIf a person reads the value (label, title, description, placeholder), add the parameter to resources/i18n.php. "
        .'If it is technical (slug, key, hook, capability, format, icon, class name), add it to TECHNICAL_PARAMETERS in this test.',
        implode("\n  ", $unclassed),
    ));
});

it('classes a parameter once', function (): void {
    $both = [];

    foreach (declaredTranslatableFields() as $class => $fields) {
        foreach (array_intersect($fields, declaredTechnicalParameters()[$class] ?? []) as $field) {
            $both[] = $class.'::$'.$field;
        }
    }

    expect($both)->toBe([], 'A parameter is either translatable (resources/i18n.php) or technical (TECHNICAL_PARAMETERS), not both.');
});

it('declares only parameters that exist and can hold text', function (): void {
    $parameters = textParametersOfAttributes();
    $stale = [];

    foreach ([declaredTranslatableFields(), declaredTechnicalParameters()] as $classification) {
        foreach ($classification as $class => $fields) {
            foreach ($fields as $field) {
                if (! in_array($field, $parameters[$class] ?? [], true)) {
                    $stale[] = $class.'::$'.$field;
                }
            }
        }
    }

    expect($stale)->toBe([], 'These entries match no text parameter of an attribute any more: remove or rename them.');
});

it('declares the file in composer.json and ships it', function (): void {
    $composer = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/composer.json'), true, flags: JSON_THROW_ON_ERROR);
    $exported = (string) file_get_contents(dirname(__DIR__, 2).'/.gitattributes');

    expect($composer['extra']['pollora']['i18n'])->toBe('resources/i18n.php')
        ->and(dirname(__DIR__, 2).'/resources/i18n.php')->toBeFile()
        ->and($exported)->not->toContain('/resources');
});
