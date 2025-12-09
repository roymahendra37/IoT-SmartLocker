<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySmartLockerKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-API-KEY');
        $validKey = env('SMARTLOCKER_API_KEY');

        if ($key !== $validKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}