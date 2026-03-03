<?php

declare(strict_types=1);

namespace Lubix\Core\Support;

final class Vite
{
    private static ?array $manifest = null;

    public static function tags(string $entry = 'index.html'): string
    {
        $env = Env::get('APP_ENV', 'dev');

        if ($env === 'dev') {
            return <<<HTML
                <script type="module" src="/src/main.js"></script>
            HTML;
        }

        $manifestPath = __DIR__ . '/../../../public/assets/.vite/manifest.json';
        if (!is_file($manifestPath)) {
            $manifestPath = __DIR__ . '/../../../public/assets/manifest.json';
        }

        if (self::$manifest === null && is_file($manifestPath)) {
            self::$manifest = json_decode((string)file_get_contents($manifestPath), true) ?: [];
        }

        $chunk = self::$manifest[$entry] ?? null;
        if (!$chunk) {
            return '';
        }

        $html = '<script type="module" src="/assets/' . $chunk['file'] . '"></script>';
        
        if (isset($chunk['css'])) {
            foreach ($chunk['css'] as $cssFile) {
                $html .= '<link rel="stylesheet" href="/assets/' . $cssFile . '">';
            }
        }

        return $html;
    }
}
