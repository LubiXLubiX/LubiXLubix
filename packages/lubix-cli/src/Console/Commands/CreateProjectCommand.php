<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class CreateProjectCommand implements CommandInterface
{
    public function name(): string
    {
        return 'create-project';
    }

    public function description(): string
    {
        return 'Create a new LubiX project';
    }

    public function run(array $args): int
    {
        $projectName = $args[0] ?? null;
        
        if (!$projectName) {
            fwrite(STDERR, "Error: Project name is required\n\n");
            fwrite(STDERR, "Usage:\n");
            fwrite(STDERR, "  lubix create-project [name]\n\n");
            return 1;
        }

        echo "🚀 [Deca] Creating project '{$projectName}'...\n";
        
        // Create project directory
        if (!mkdir($projectName, 0755, true)) {
            fwrite(STDERR, "Error: Failed to create project directory\n");
            return 1;
        }

        // Get template path (using current LubiX as template)
        $templatePath = __DIR__ . '/../../../../../';
        $projectPath = getcwd() . '/' . $projectName;

        // Copy essential files
        $essentialFiles = [
            'composer.json',
            'composer.lock',
            '.env.example',
            '.gitignore',
            'README.md',
            'packages',
            'app',
            'bootstrap',
            'config',
            'public',
            'resources',
            'routes',
            'tsconfig.json',
            'vite.config.js',
            'package.json',
            'package-lock.json'
        ];

        foreach ($essentialFiles as $file) {
            $source = $templatePath . '/' . $file;
            $dest = $projectPath . '/' . $file;
            
            if (file_exists($source)) {
                if (is_dir($source)) {
                    $this->copyDirectory($source, $dest);
                } else {
                    copy($source, $dest);
                }
                echo "✓ Copied {$file}\n";
            }
        }

        // Update composer.json with new project name
        $composerJsonPath = $projectPath . '/composer.json';
        if (file_exists($composerJsonPath)) {
            $composer = json_decode(file_get_contents($composerJsonPath), true);
            if (isset($composer['name'])) {
                $composer['name'] = strtolower($projectName) . '/' . strtolower($projectName);
                file_put_contents($composerJsonPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }

        echo "📦 Project '{$projectName}' created successfully!\n\n";
        echo "Next steps:\n";
        echo "  cd {$projectName}\n";
        echo "  composer install\n";
        echo "  cp .env.example .env\n";
        echo "  lubix serve\n\n";
        echo "Happy coding! 🎉\n";

        return 0;
    }

    private function copyDirectory(string $source, string $dest): void
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $files = scandir($source);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $source . '/' . $file;
            $destPath = $dest . '/' . $file;

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
    }
}
