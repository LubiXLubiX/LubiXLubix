<?php

declare(strict_types=1);

namespace Lubix\Cli\Console;

interface CommandInterface
{
    public function name(): string;

    public function description(): string;

    /** @param array<int, string> $args */
    public function run(array $args): int;
}
