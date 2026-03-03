<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;

final class MakeControllerCommand implements CommandInterface
{
    public function name(): string
    {
        return 'make:controller';
    }

    public function description(): string
    {
        return 'Create a new controller';
    }

    public function run(array $args): int
    {
        $controllerName = $args[0] ?? null;
        
        if (!$controllerName) {
            fwrite(STDERR, "Error: Controller name is required\n\n");
            fwrite(STDERR, "Usage:\n");
            fwrite(STDERR, "  lubix make:controller <ControllerName>\n\n");
            return 1;
        }

        // Ensure Controller suffix
        if (!str_ends_with($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }

        $appPath = getcwd() . '/app/Http/Controllers';
        
        // Create directory if it doesn't exist
        if (!is_dir($appPath)) {
            mkdir($appPath, 0755, true);
        }

        $filePath = $appPath . '/' . $controllerName . '.php';
        
        if (file_exists($filePath)) {
            fwrite(STDERR, "Error: Controller {$controllerName} already exists\n");
            return 1;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;

class {$controllerName}
{
    public function index(Request \$request): Response
    {
        return Response::json([
            'message' => '{$controllerName} index method'
        ]);
    }

    public function show(Request \$request, array \$params): Response
    {
        \$id = \$params['id'] ?? null;
        
        return Response::json([
            'message' => '{$controllerName} show method',
            'id' => \$id
        ]);
    }

    public function store(Request \$request): Response
    {
        \$data = \$request->json();
        
        return Response::json([
            'message' => '{$controllerName} store method',
            'data' => \$data
        ], 201);
    }

    public function update(Request \$request, array \$params): Response
    {
        \$id = \$params['id'] ?? null;
        \$data = \$request->json();
        
        return Response::json([
            'message' => '{$controllerName} update method',
            'id' => \$id,
            'data' => \$data
        ]);
    }

    public function destroy(Request \$request, array \$params): Response
    {
        \$id = \$params['id'] ?? null;
        
        return Response::json([
            'message' => '{$controllerName} destroy method',
            'id' => \$id
        ]);
    }
}
PHP;

        if (file_put_contents($filePath, $template)) {
            echo "✓ Controller {$controllerName} created successfully\n";
            echo "  Path: app/Http/Controllers/{$controllerName}.php\n";
            return 0;
        }

        fwrite(STDERR, "Error: Failed to create controller\n");
        return 1;
    }
}
