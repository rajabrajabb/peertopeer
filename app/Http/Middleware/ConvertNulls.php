<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertNulls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $request->merge($this->convertNullStringsToNull($request->all()));

        return $next($request);
    }

    /**
     * Convert "null" strings to actual null values in the request data.
     *
     * @param  array  $data
     * @return array
     */
    protected function convertNullStringsToNull(array $data)
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->convertNullStringsToNull($value);
            }
            return $value === 'null' ? null : $value;
        }, $data);
    }
}
