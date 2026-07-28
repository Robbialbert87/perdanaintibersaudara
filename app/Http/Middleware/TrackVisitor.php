<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$request->is('admin/*') && !$request->is('login') && !$request->is('api/*')) {
            Visitor::create([
                'ip' => $request->ip(),
                'url' => $request->url(),
                'user_agent' => $request->userAgent(),
                'created_at' => now('Asia/Jakarta'),
            ]);
        }

        return $response;
    }
}
