<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = request()->bearerToken();
        \Log::info('token: '.$token);
        if (!$token) {
            return response()->unauthorized('Token not provided');
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'));
            \Log::info($credentials);
        } catch (ExpiredException $e) {
            return response()->unauthorized('Provided token is expired');
        } catch (Exception $e) {
            return response()->unauthorized('An error while decoding token');
        }

        return $next($request);
    }
}
