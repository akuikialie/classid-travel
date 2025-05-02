<?php

namespace App\Http\Controllers\Api\Options;

use App\Exceptions\CidException;
use App\Http\Controllers\Api\Controller as BaseController;
use App\Http\Response;
use App\Models\Jamaah\Jamaah;
use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Queries\JamaahQuery;
use App\Queries\UserQuery;
use App\Services\Inbound\InboundService;
use App\Services\PaymentService;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function Symfony\Component\String\u;

#[Prefix('')]
#[Name('', true, false)]
class OptionSelectController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     */
    #[Get('users', name: 'users')]
    public function users(Request $request): \App\Http\Response
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'string']
        ]);
        $users = UserQuery::byTenant(Tenant::hashToId($validated['tenant_id']))
            ->filterColumn()
            ->orderColumn()
            ->build()
            ->get();

        $data = $users->map(function ($user) {
            return [
                'id' => $user->hash,
                'name' => $user->name,
            ];
        });

        return $this->response($data);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Get('jamaah', name: 'jamaah')]
    public function jamaah(Request $request): \App\Http\Response
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'string']
        ]);
        $jamaah = JamaahQuery::byTenant(Tenant::hashToId($validated['tenant_id']))
            ->filterColumn()
            ->orderColumn()
            ->build()
            ->get();

        $data = $jamaah->map(function ($jamaah) {
            return [
                'id' => $jamaah->hash,
                'name' => $jamaah->user->name,
            ];
        });

        return $this->response($data);
    }


    /**
     * @param Request $request
     * @return Response
     */
    #[Get('get-saving', name: 'get-saving')]
    public function getSaving(Request $request): \App\Http\Response
    {
        $validated = $request->validate([
            'user_id' => ['required', 'string']
        ]);
        $user = User::byHash($validated['user_id']);

        if (!$user instanceof User) {
            throw new ModelNotFoundException();
        }

        $userSavings = [
            [
                'id' => $user->tabungan->hash,
                'name' => $user->tabungan->name,
                'balance' => 'Rp. '. moneyFormat($user->tabungan->balance),
            ]
        ];

        foreach ($user->jamaah->tabunganPackages as $saving) {
            $userSavings[] = [
                'id' => $saving->hash,
                'name' => $saving->name,
                'balance' => 'Rp. '. moneyFormat($saving->balance),
            ];
        }

        return $this->response($userSavings);
    }

}
