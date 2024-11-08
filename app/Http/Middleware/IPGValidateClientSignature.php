<?php

namespace App\Http\Middleware;

use App\Enums\ResponseCode;
use App\Exceptions\CidException;
use Closure;
use Illuminate\Http\Request;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class IPGValidateClientSignature
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws CidException
     * @throws JsonException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('Signature')) {
            throw new CidException(ResponseCode::ERR_MISSING_SIGNATURE_HEADER);
        }

        $apiToken = env('IPG_API_TOKEN');
        $payload = request()->all();
        $expected = hash_hmac('sha256', json_encode($payload, JSON_THROW_ON_ERROR), $apiToken);

        if ($expected !== request()->header('Signature')) {
            $data = [
                'signature' => request()->header('Signature'), 'payload' => request()->all(),
            ];

            if ('production' !== env('APP_ENV')) {
                $data = array_merge([
                    'expected' => [
                        'api_token' => $apiToken,
                        'signature' => $expected,
                    ],
                ], $data);
            }

            if ('pass' != env('APP_BYPASS_SIGNATURE', 'false')) {
                throw new CidException(rc: ResponseCode::ERR_INVALID_SIGNATURE_HEADER, data: $data);
            }
        }

        return $next($request);
    }
}
