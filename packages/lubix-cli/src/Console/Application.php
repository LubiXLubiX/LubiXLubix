<?php

declare(strict_types=1);

namespace Lubix\Cli\Console;

use Lubix\Cli\Console\CommandInterface;
use Lubix\Cli\Console\Commands\DevCommand;
use Lubix\Cli\Console\Commands\DbCreateCommand;
use Lubix\Cli\Console\Commands\MigrateCommand;
use Lubix\Cli\Console\Commands\RollbackCommand;
use Lubix\Cli\Console\Commands\ServeCommand;
use Lubix\Cli\Console\Commands\CreateProjectCommand;
use Lubix\Cli\Console\Commands\MakeControllerCommand;
use Lubix\Cli\Console\Commands\MakeModelCommand;
use Lubix\Cli\Console\Commands\MakeMigrationCommand;

final class Application
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    public function __construct()
    {
        $this->register(new CreateProjectCommand());
        $this->register(new DevCommand());
        $this->register(new ServeCommand());
        $this->register(new DbCreateCommand());
        $this->register(new MigrateCommand());
        $this->register(new RollbackCommand());
        $this->register(new MakeControllerCommand());
        $this->register(new MakeModelCommand());
        $this->register(new MakeMigrationCommand());
    }

    public function register(CommandInterface $command): void
    {
        $this->commands[$command->name()] = $command;
    }

    public function getCommand(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }

    public function run(array $argv): int
    {
        $name = $argv[1] ?? 'help';

        if ($name === 'help' || $name === '--help' || $name === '-h') {
            $this->printHelp();
            return 0;
        }

        $command = $this->commands[$name] ?? null;
        if ($command === null) {
            fwrite(STDERR, "Unknown command: {$name}\n\n");
            $this->printHelp();
            return 1;
        }

        $args = array_slice($argv, 2);
        return $command->run($args);
    }

    private function printHelp(): void
    {
        echo "LubiX CLI\n\n";
        echo "Usage:\n";
        echo "  lubix <command> [args]\n\n";
        echo "Commands:\n";
        foreach ($this->commands as $name => $cmd) {
            echo "  {$name}\t" . $cmd->description() . "\n";
        }
        echo "\n";
    }
}
