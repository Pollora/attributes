<?php

declare(strict_types=1);

use Pollora\Harness\Pest\Architecture;

/*
 * Architecture rules of the Pollora harness (ARCH-*, CONV-*).
 *
 * The rules follow the profile in .harness.json and the layers found under
 * the PSR-4 roots: nothing to maintain here. List them with
 * `vendor/bin/pollora-qa rules`.
 */

/*
 * ARCH-08 is left out on purpose, for this repository only.
 *
 * The attribute classes are published (since v0.1.0) and extended or mutated
 * by the packages built on them: making them `final` and their public
 * properties `readonly` would change the public contract, so it waits for a
 * major version. Only ARCH-08 is skipped; every other rule below stays active.
 * Remove this block, and the CHANGELOG entry that goes with it, when the
 * attributes are made final and readonly in the next major release.
 */
beforeEach(function (): void {
    if (str_contains($this->name(), 'ARCH_08')) {
        $this->markTestSkipped('ARCH-08 waits for the next major version: final/readonly would change the published contract.');
    }
});

Architecture::for(dirname(__DIR__))->register();
