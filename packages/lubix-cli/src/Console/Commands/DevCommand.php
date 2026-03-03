<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class DevCommand implements CommandInterface
{
    public function name(): string
    {
        return 'dev';
    }

    public function description(): string
    {
        return 'Run backend (php -S) + frontend (vite) dev servers together';
    }

    public function run(array $args): int
    {
        $phpHost = '127.0.0.1';
        $phpPort = '8000';
        $vitePort = '5173';

        $cwd = getcwd() ?: '.';
        $repoRoot = $this->detectRepoRoot($cwd) ?? $cwd;
        $publicPath = $repoRoot . '/public';

        $phpCmd = sprintf('php -S %s:%s -t %s', escapeshellarg($phpHost), escapeshellarg($phpPort), escapeshellarg($publicPath));
        $viteCmd = sprintf('npm run dev -- --port %s', escapeshellarg($vitePort));

        echo "\n\033[1;34mLubiX Professional Stack\033[0m\n";
        echo "--------------------------\n";
        echo "\033[1;32m➜\033[0m  Local: \033[1;36mhttp://localhost:{$vitePort}\033[0m\n";
        echo "--------------------------\n";
        echo "\033[0;90mStarting servers...\033[0m\n\n";

        $descriptorspec = [
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR
        ];

        // Start Backend
        $phpProc = proc_open($phpCmd, $descriptorspec, $phpPipes, $repoRoot);
        if (!is_resource($phpProc)) {
            fwrite(STDERR, "Failed to start backend server\n");
            return 1;
        }

        // Start Frontend
        $viteProc = proc_open($viteCmd, $descriptorspec, $vitePipes, $repoRoot);
        if (!is_resource($viteProc)) {
            proc_terminate($phpProc);
            fwrite(STDERR, "Failed to start Vite dev server\n");
            return 1;
        }

        // Handle signals if possible (PHP 7.1+)
        if (function_exists('pcntl_signal')) {
            pcntl_async_signals(true);
            $handler = function() use ($phpProc, $viteProc) {
                proc_terminate($phpProc);
                proc_terminate($viteProc);
                exit(0);
            };
            pcntl_signal(SIGINT, $handler);
            pcntl_signal(SIGTERM, $handler);
        }

        // Keep-alive loop
        while (true) {
            $phpStatus = proc_get_status($phpProc);
            $viteStatus = proc_get_status($viteProc);

            if (!$phpStatus['running'] || !$viteStatus['running']) {
                break;
            }
            usleep(200000);
        }

        proc_terminate($phpProc);
        proc_terminate($viteProc);
        return 0;
    }

    private function detectRepoRoot(string $startDir): ?string
    {
        $dir = $startDir;
        for ($i = 0; $i < 15; $i++) {
            if (is_dir($dir . '/public') && is_dir($dir . '/packages') && is_dir($dir . '/resources')) {
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
