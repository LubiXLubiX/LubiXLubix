<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class ServeCommand implements CommandInterface
{
    public function name(): string
    {
        return 'serve';
    }

    public function description(): string
    {
        return 'Run PHP dev server for an app (default: apps/demo-backend)';
    }

    public function run(array $args): int
    {
        $quiet = true;
        $verbose = false;

        $filtered = [];
        foreach ($args as $arg) {
            if ($arg === '--verbose' || $arg === '-v') {
                $verbose = true;
                $quiet = false;
                continue;
            }
            if ($arg === '--quiet' || $arg === '-q') {
                $quiet = true;
                continue;
            }
            $filtered[] = $arg;
        }
        $args = $filtered;

        $host = $args[1] ?? '127.0.0.1';
        $port = $args[2] ?? '8000';

        $cwd = getcwd() ?: '.';
        $repoRoot = $this->detectRepoRoot($cwd);

        // If running from the monorepo root and no explicit target passed, serve root ./public.
        if (!isset($args[0]) && $repoRoot !== null && realpath($cwd) === realpath($repoRoot) && is_dir($repoRoot . '/public')) {
            $appPath = $repoRoot;
        }

        // If executed inside an app directory (has ./public), serve it by default.
        if (!isset($args[0]) && !isset($appPath) && is_dir($cwd . '/public')) {
            $appPath = $cwd;
        } else {
            if (!isset($appPath)) {
                $target = $args[0] ?? '.';
                $base = $repoRoot ?? $cwd;
                $appPath = rtrim($base, '/') . '/' . trim($target, '/');
            }
        }

        $publicPath = rtrim($appPath, '/') . '/public';

        if (!is_dir($publicPath)) {
            fwrite(STDERR, "Public directory not found: {$publicPath}\n");
            return 1;
        }

        $cmd = sprintf('php -S %s:%s -t %s', escapeshellarg($host), escapeshellarg($port), escapeshellarg($publicPath));

        if (!$quiet) {
            echo "LubiX CLI\n";
            echo "Command : serve\n";
            echo "App     : {$appPath}\n";
            echo "URL     : http://{$host}:{$port}\n";
            if ($verbose) {
                echo "Exec    : {$cmd}\n";
            }
            echo "\n";
        } else {
            echo "LubiX dev server: http://{$host}:{$port}\n";
            echo "Tip: run `php lubix dev` to start backend + frontend together.\n";
        }

        passthru($cmd, $exitCode);
        return (int) $exitCode;
    }

    private function detectRepoRoot(string $startDir): ?string
    {
        $dir = $startDir;
        for ($i = 0; $i < 15; $i++) {
            if (is_dir($dir . '/apps') && is_dir($dir . '/packages')) {
                return $dir;
            }

            $parent = dirname($dir);
            if ($parent === $dir) {
                break;
            }
            $dir = $parent;
        }

        return null;
    }
}
