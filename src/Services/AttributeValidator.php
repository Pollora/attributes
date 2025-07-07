<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\Attributable;
use Pollora\Attributes\Contracts\AttributeCompatibility;
use Pollora\Attributes\Contracts\TypedAttribute;
use Pollora\Attributes\Exceptions\AttributeValidationException;

/**
 * Service for validating attribute configurations and domain compatibility.
 *
 * This class ensures that attributes are properly configured and that
 * domain combinations are compatible according to the registry rules.
 */
class AttributeValidator
{
    /**
     * Create a new AttributeValidator instance.
     *
     * @param  AttributeRegistry  $registry  The attribute registry
     */
    public function __construct(private readonly AttributeRegistry $registry) {}

    /**
     * Validates domain compatibility for an attributable instance.
     *
     * @param  Attributable  $instance  The attributable instance
     * @param  array<string, array>  $attributesByDomain  Attributes grouped by domain
     *
     * @throws AttributeValidationException If validation fails
     */
    public function validateDomainCompatibility(
        Attributable $instance,
        array $attributesByDomain
    ): void {
        $domains = array_keys($attributesByDomain);

        // Check that the instance supports all domains
        foreach ($domains as $domain) {
            if (! $instance->supportsDomain($domain)) {
                throw new AttributeValidationException(
                    sprintf(
                        'Instance of %s does not support domain %s',
                        $instance::class,
                        $domain
                    )
                );
            }
        }

        // Check compatibility between domains using attributes
        $this->validateAttributeCompatibility($attributesByDomain);

        // Validate individual attributes
        foreach ($attributesByDomain as $domain => $attributes) {
            foreach ($attributes as $attributeData) {
                $this->validateAttribute($attributeData['instance'], $domain);
            }
        }
    }

    /**
     * Validates an individual attribute against its domain.
     *
     * @param  object  $attribute  The attribute instance
     * @param  string  $domain  The domain being processed
     *
     * @throws AttributeValidationException If validation fails
     */
    private function validateAttribute(object $attribute, string $domain): void
    {
        if ($attribute instanceof TypedAttribute && ! $attribute->supportsDomain($domain)) {
            throw new AttributeValidationException(
                sprintf(
                    'Attribute %s does not support domain %s',
                    $attribute::class,
                    $domain
                )
            );
        }
    }

    /**
     * Validates compatibility between domains using attribute-level compatibility rules.
     *
     * @param  array<string, array>  $attributesByDomain  Attributes grouped by domain
     *
     * @throws AttributeValidationException If validation fails
     */
    private function validateAttributeCompatibility(array $attributesByDomain): void
    {
        $domains = array_keys($attributesByDomain);

        // For each attribute, check if it's compatible with all other domains present
        foreach ($attributesByDomain as $domain => $attributes) {
            foreach ($attributes as $attributeData) {
                $attribute = $attributeData['instance'];

                if ($attribute instanceof AttributeCompatibility) {
                    // Check compatibility with all other domains
                    foreach ($domains as $otherDomain) {
                        if ($domain !== $otherDomain && ! $attribute->isCompatibleWith($otherDomain)) {
                            throw new AttributeValidationException(
                                sprintf(
                                    'Attribute %s (domain: %s) is not compatible with domain %s',
                                    $attribute::class,
                                    $domain,
                                    $otherDomain
                                )
                            );
                        }
                    }
                } else {
                    // Fallback to registry-based compatibility for attributes that don't implement AttributeCompatibility
                    foreach ($domains as $otherDomain) {
                        if ($domain !== $otherDomain && ! $this->registry->areDomainsCompatible($domain, $otherDomain)) {
                            throw new AttributeValidationException(
                                sprintf(
                                    'Domains %s and %s are not compatible (fallback validation)',
                                    $domain,
                                    $otherDomain
                                )
                            );
                        }
                    }
                }
            }
        }
    }
}
