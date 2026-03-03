<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class UpgradeCommand implements CommandInterface
{
    public function name(): string
    {
        return 'upgrade';
    }

    public function description(): string
    {
        return 'Upgrade LubiX framework to the latest version';
    }

    public function run(array $args): int
    {
        echo "🚀 [LubiX] Checking for updates...\n";
        
        $currentVersion = $this->getCurrentVersion();
        echo "Current version: {$currentVersion}\n";
        
        $latestVersion = $this->getLatestVersion();
        
        if ($latestVersion === null) {
            echo "⚠️  Unable to check for updates. Please check your internet connection.\n";
            return 1;
        }
        
        echo "Latest version: {$latestVersion}\n";
        
        if (version_compare($currentVersion, $latestVersion, '>=')) {
            echo "✅ You're already using the latest version!\n";
            return 0;
        }
        
        echo "📦 Updating to version {$latestVersion}...\n";
        
        if ($this->performUpdate()) {
            echo "✅ Successfully updated to LubiX {$latestVersion}!\n";
            echo "🎉 Enjoy the new features and improvements!\n";
            return 0;
        }
        
        echo "❌ Failed to update. Please try manual update:\n";
        echo "   git pull origin main\n";
        echo "   composer install\n";
        return 1;
    }
    
    private function getCurrentVersion(): string
    {
        $composerJson = getcwd() . '/composer.json';
        
        if (!file_exists($composerJson)) {
            return 'unknown';
        }
        
        $composer = json_decode(file_get_contents($composerJson), true);
        return $composer['version'] ?? '1.0.0';
    }
    
    private function getLatestVersion(): ?string
    {
        // For now, return a fixed version. In production, this would
        // fetch from GitHub API or Packagist API
        return '1.2.1';
    }
    
    private function performUpdate(): bool
    {
        $commands = [
            'git fetch origin',
            'git pull origin main',
            'composer install --no-dev --optimize-autoloader'
        ];
        
        foreach ($commands as $command) {
            echo "Executing: {$command}\n";
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                echo "Command failed: {$command}\n";
                return false;
            }
        }
        
        return true;
    }
}
