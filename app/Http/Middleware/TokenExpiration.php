<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        // Check if the user is authenticated
        if ($user) {
            $token = $user->currentAccessToken();
            if ($token) {
                $created = $token->created_at;
                $expiresAt = Carbon::parse($created)->addDay();
                if (Carbon::now()->greaterThan($expiresAt)) {
                    foreach ($user->tokens as $token) {
                        $token->delete();
                    }
                    return response()->json(['message' => 'Unauthorized. Token has expired. Please login again.'], 401);
                }
            }
        } else {
            return response()->json(['message' => 'Unauthorized. Please login again.'], 401);
        }

        return $next($request);
    }
}
