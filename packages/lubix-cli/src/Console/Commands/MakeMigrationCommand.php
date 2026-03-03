<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class MakeMigrationCommand implements CommandInterface
{
    public function name(): string
    {
        return 'make:migration';
    }

    public function description(): string
    {
        return 'Create a new migration';
    }

    public function run(array $args): int
    {
        $migrationName = $args[0] ?? null;
        
        if (!$migrationName) {
            fwrite(STDERR, "Error: Migration name is required\n\n");
            fwrite(STDERR, "Usage:\n");
            fwrite(STDERR, "  lubix make:migration <migration_name>\n\n");
            return 1;
        }

        $databasePath = getcwd() . '/database/migrations';
        
        // Create directory if it doesn't exist
        if (!is_dir($databasePath)) {
            mkdir($databasePath, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $fileName = $timestamp . '_' . $migrationName . '.php';
        $filePath = $databasePath . '/' . $fileName;
        
        if (file_exists($filePath)) {
            fwrite(STDERR, "Error: Migration {$migrationName} already exists\n");
            return 1;
        }

        $className = $this->toCamelCase($migrationName);
        
        $template = <<<PHP
<?php

declare(strict_types=1);

namespace Database\Migrations;

use Lubix\ORM\Migrations\Migration;
use Lubix\ORM\Database\Schema;
use Lubix\ORM\Database\Blueprint;

class {$className} extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
}
PHP;

        if (file_put_contents($filePath, $template)) {
            echo "✓ Migration {$migrationName} created successfully\n";
            echo "  Path: database/migrations/{$fileName}\n";
            return 0;
        }

        fwrite(STDERR, "Error: Failed to create migration\n");
        return 1;
    }

    private function toCamelCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}
