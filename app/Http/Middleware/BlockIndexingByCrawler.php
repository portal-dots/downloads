<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIndexingByCrawler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Illuminate\Http\Response */
        $response = $next($request);

        return $response->header('X-Robots-Tag', 'noindex');
    }
}
