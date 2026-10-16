<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CleanHtmlSource
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if ($response->getStatusCode() === 200 && $this->isHtml($response)) {
            $html = $response->getContent();

            if (!empty($html)) {
                // 1. 移除 Blade 指令產生的所有「純空白行」
                $html = preg_replace('/^[ \t]*[\r\n]+/m', '', $html);

                // 2. 解決階梯式右移：將每行開頭超過 12 個空格的超深縮排重置/收攏
                $html = preg_replace_callback('/^[ \t]+/m', function ($matches) {
                    $indentLength = strlen(str_replace("\t", '    ', $matches[0]));
                    // 限制最大縮排為 12 個空格（約 3 層 tab），超過的全部平鋪，防止一路推到最右邊
                    $newIndent = min($indentLength, 12);
                    return str_repeat(' ', $newIndent);
                }, $html);

                $response->setContent($html);
            }
        }

        return $response;
    }

    protected function isHtml(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return $contentType && str_contains($contentType, 'text/html');
    }
}
