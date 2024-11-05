<?php

namespace App\Services\Auth;

use App\Contracts\Wallet\VirtualAccountInterface;
use App\Enums\ResponseCode;
use App\Exceptions\CidException;
use App\Exceptions\CoopException;
use App\Models\Account\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OAuth2IPGService
{
    /**
     * @return array
     *
     * @throws CoopException
     */
    public function createAccessToken(): array
    {
        $oauth2Config = [
            'grant_type' => config('billing.IPG_data.grant_type'),
            'client_id' => config('billing.IPG_data.client_id'),
            'client_secret' => config('billing.IPG_data.client_secret'),
        ];

        $authorizationResponse = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post(config('billing.IPG_endpoint.access_token_url'), $oauth2Config);

        $data = json_decode($authorizationResponse->body(), true);
        if (! $authorizationResponse->ok()) {
            throw new CidException(ResponseCode::from($data['rc']), message: $data['message']);
        }

        return $data;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getAccessToken(VirtualAccountInterface $user): array
    {
        // Step 1: Obtain an access token by making an authorization request
        return Cache::remember("DEP:{$user->id}:TOKEN", now()->addHour(config('billing.expired')), function () {
            $data = $this->createAccessToken();

            return [
                'token_type' => $data['token_type'],
                'access_token' => $data['access_token'],
            ];
        });
    }
}
