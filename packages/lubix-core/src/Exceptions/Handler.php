<?php

declare(strict_types=1);

namespace Lubix\Core\Exceptions;

use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;
use Lubix\Core\Support\Env;
use Throwable;

final class Handler
{
    public function render(Request $request, Throwable $e): Response
    {
        $debug = Env::get('APP_DEBUG') === 'true';
        $isApi = str_starts_with($request->path, '/api/');

        if ($isApi) {
            return $this->renderJsonResponse($e, $debug);
        }

        return $this->renderHtmlResponse($e, $debug);
    }

    private function renderJsonResponse(Throwable $e, bool $debug): Response
    {
        $statusCode = ($e instanceof HttpException) ? $e->statusCode : 500;
        
        $data = [
            'ok' => false,
            'message' => $e->getMessage(),
        ];

        if ($debug) {
            $data['exception'] = get_class($e);
            $data['file'] = $e->getFile();
            $data['line'] = $e->getLine();
            $data['trace'] = explode("\n", $e->getTraceAsString());
        }

        return Response::json($data, $statusCode);
    }

    private function renderHtmlResponse(Throwable $e, bool $debug): Response
    {
        $statusCode = ($e instanceof HttpException) ? $e->statusCode : 500;
        
        if ($statusCode === 404 && !$debug) {
            return Response::html($this->get404Html(), 404);
        }

        if (!$debug) {
            return Response::html($this->getSimpleHtml($statusCode, $e->getMessage()), $statusCode);
        }

        return Response::html($this->getDebugHtml($e), $statusCode);
    }

    private function get404Html(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html class="dark">
<head>
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #050505; color: #e5e7eb; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-8 flex justify-center">
            <div class="bg-[#ff6f00]/10 p-4 rounded-2xl border border-[#ff6f00]/20">
                <svg class="w-12 h-12 text-[#ff6f00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <h1 class="text-6xl font-black text-white mb-4 tracking-tighter">404</h1>
        <p class="text-xl text-gray-400 mb-10 font-medium">Oops! The page you're looking for has vanished into thin air.</p>
        <a href="/" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-white text-black font-bold hover:bg-gray-200 transition-colors">
            Back to Home
        </a>
    </div>
</body>
</html>
HTML;
    }

    private function getSimpleHtml(int $code, string $message): string
    {
        $title = match($code) {
            404 => 'Page Not Found',
            403 => 'Forbidden',
            401 => 'Unauthorized',
            400 => 'Bad Request',
            500 => 'Server Error',
            503 => 'Service Unavailable',
            default => 'Error'
        };

        return <<<HTML
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$code} | {$title}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #050505; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 overflow-hidden">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,111,0,0.05)_0%,transparent_70%)]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8881_1px,transparent_1px),linear-gradient(to_bottom,#8881_1px,transparent_1px)] bg-[size:40px_40px]"></div>
    </div>
    
    <div class="relative z-10 text-center max-w-xl">
        <div class="mb-12 inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-xl shadow-2xl shadow-orange-500/10">
            <span class="text-5xl font-black text-[#FF6F00]">{$code[0]}</span>
        </div>
        <h1 class="text-7xl md:text-8xl font-black text-white mb-6 tracking-tighter">{$code}</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-400 mb-10 tracking-tight">{$title}</h2>
        <p class="text-gray-500 mb-12 leading-relaxed font-medium">{$message}</p>
        <a href="/" class="group relative inline-flex items-center justify-center px-10 py-4 rounded-2xl overflow-hidden transition-all hover:scale-105 active:scale-95">
            <div class="absolute inset-0 bg-white"></div>
            <span class="relative text-black font-black uppercase tracking-widest text-sm">Return Home</span>
        </a>
    </div>
</body>
</html>
HTML;
    }

    private function getDebugHtml(Throwable $e): string
    {
        $message = htmlspecialchars($e->getMessage());
        $class = get_class($e);
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = htmlspecialchars($e->getTraceAsString());
        $phpVersion = PHP_VERSION;
        
        return <<<HTML
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LubiX Error: {$message}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=JetBrains+Mono:wght@400;500&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #050505; color: #E2E8F0; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .error-glow { text-shadow: 0 0 30px rgba(255,111,0,0.3); }
        .code-block::-webkit-scrollbar { width: 8px; height: 8px; }
        .code-block::-webkit-scrollbar-track { background: transparent; }
        .code-block::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .code-block::-webkit-scrollbar-thumb:hover { background: #444; }
    </style>
</head>
<body class="min-h-screen selection:bg-[#FF6F00]/30 selection:text-[#FF6F00]">
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_0%_0%,rgba(255,111,0,0.08)_0%,transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8881_1px,transparent_1px),linear-gradient(to_bottom,#8881_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_at_center,#000_20%,transparent_100%)]"></div>
    </div>

    <nav class="sticky top-0 z-50 bg-[#050505]/80 backdrop-blur-xl border-b border-white/5 py-4 px-6">
        <div class="max-w-[1400px] mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#FF6F00] flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tighter text-white">LubiX <span class="text-[#FF6F00]">Ignition</span></h1>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Debug Console v1.0</p>
                </div>
            </div>
            <div class="flex items-center gap-6 text-[11px] font-bold uppercase tracking-widest text-gray-500">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-[#FF6F00]"></span>
                    PHP {$phpVersion}
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    LOCAL ENV
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto p-6 md:p-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Sidebar: Error Summary -->
            <div class="lg:col-span-5 space-y-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 text-[10px] font-black uppercase tracking-widest">
                        Unhandled Exception
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-[1.1] tracking-tight error-glow">{$message}</h2>
                    <p class="text-lg text-gray-400 font-medium leading-relaxed">{$class}</p>
                </div>

                <div class="space-y-6">
                    <div class="p-6 rounded-2xl bg-[#111] border border-white/5 shadow-2xl">
                        <div class="text-[11px] font-black text-gray-500 uppercase tracking-widest mb-4">Exception Location</div>
                        <div class="space-y-3">
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-black/40 border border-white/5">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs text-gray-500 mb-1 truncate">{$file}</p>
                                    <p class="text-sm text-white font-bold mono">Line <span class="text-[#FF6F00]">{$line}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content: Trace & Source -->
            <div class="lg:col-span-7 space-y-8">
                <div class="rounded-3xl bg-[#0A0A0A] border border-white/5 shadow-[0_0_100px_rgba(0,0,0,0.5)] overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 bg-[#111] border-b border-white/5">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                            <div class="w-3 h-3 rounded-full bg-orange-500/40"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Stack Trace</div>
                        <div class="w-12"></div>
                    </div>
                    <div class="code-block p-8 overflow-y-auto max-h-[600px] mono text-sm leading-relaxed text-gray-500">
                        <pre class="whitespace-pre-wrap">{$trace}</pre>
                    </div>
                </div>

                <div class="p-8 rounded-3xl bg-white/[0.02] border border-white/5 backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#FF6F00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-white tracking-tight">Need help?</h4>
                            <p class="text-sm text-gray-500 font-medium">Check the LubiX documentation for common error solutions.</p>
                        </div>
                    </div>
                    <a href="/docs" class="inline-flex items-center gap-2 text-[#FF6F00] font-black uppercase tracking-widest text-xs hover:gap-3 transition-all">
                        View Documentation <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
HTML;
    }
}
