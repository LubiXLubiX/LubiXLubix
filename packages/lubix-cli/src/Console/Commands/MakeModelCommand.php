<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class MakeModelCommand implements CommandInterface
{
    public function name(): string
    {
        return 'make:model';
    }

    public function description(): string
    {
        return 'Create a new model';
    }

    public function run(array $args): int
    {
        $modelName = $args[0] ?? null;
        
        if (!$modelName) {
            fwrite(STDERR, "Error: Model name is required\n\n");
            fwrite(STDERR, "Usage:\n");
            fwrite(STDERR, "  lubix make:model <ModelName>\n\n");
            return 1;
        }

        $appPath = getcwd() . '/app/Models';
        
        // Create directory if it doesn't exist
        if (!is_dir($appPath)) {
            mkdir($appPath, 0755, true);
        }

        $filePath = $appPath . '/' . $modelName . '.php';
        
        if (file_exists($filePath)) {
            fwrite(STDERR, "Error: Model {$modelName} already exists\n");
            return 1;
        }

        $tableName = strtolower(preg_replace('/([A-Z])/', '_$1', lcfirst($modelName)));
        
        $template = <<<PHP
<?php

declare(strict_types=1);

namespace App\Models;

use Lubix\ORM\Model\Model;

class {$modelName} extends Model
{
    protected string \$table = '{$tableName}';
    
    protected array \$fillable = [
        // Add your fillable fields here
    ];
    
    protected array \$hidden = [
        // Add your hidden fields here
    ];
    
    protected array \$casts = [
        // Add your type casts here
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
PHP;

        if (file_put_contents($filePath, $template)) {
            echo "✓ Model {$modelName} created successfully\n";
            echo "  Path: app/Models/{$modelName}.php\n";
            echo "  Table: {$tableName}\n";
            return 0;
        }

        fwrite(STDERR, "Error: Failed to create model\n");
        return 1;
    }
}
