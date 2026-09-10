<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckAuthFrontend
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Access token not provided.',
            ], 401);
        }

        try {
            $decode = JWT::decode(
                $token,
                new Key(env('JWT_SECRET'), 'HS256')
            );
            if (empty($decode->data->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue.',
                ], 401);
            }

            $request->attributes->set('userData', $decode->data);
            return $next($request);

        } catch (\Firebase\JWT\ExpiredException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Expired access token.',
            ], 401);

        } catch (\Firebase\JWT\SignatureInvalidException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Access token signature is invalid.',
            ], 401);

        } catch (\UnexpectedValueException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Token signature is invalid.',
            ], 401);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid access token.',
            ], 401);
        }

   }
}