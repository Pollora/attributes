<?php

declare(strict_types=1);

use Pollora\Attributes\WpCli\Command;

it('uses the name given to the subcommand', function (): void {
    expect((new Command('flush-all'))->getSubcommandName('purgeEverything'))->toBe('flush-all');
});

it('derives the subcommand name from the method when none is given', function (): void {
    expect((new Command)->getSubcommandName('purgeEverything'))->toBe('purge-everything');
});
