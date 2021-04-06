<?php

namespace App\Http\Middleware;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('api/*')) {
            throw new HttpResponseException(response()->json(['failure_reason' => 'Fresh Access Token Required'], 403));
        }
        if (!$request->expectsJson()) {
            return response()->json(['failure_reason' => 'Fresh Access Token Required'], 403);
        }
    }
}
