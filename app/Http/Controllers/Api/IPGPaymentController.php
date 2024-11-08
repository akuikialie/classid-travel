<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller as BaseController;
use App\Models\User;
use App\Services\Inbound\InboundService;
use App\Services\PaymentService;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;

#[Prefix('')]
#[Name('', true, true)]
#[Middleware(['ipg_validate_client_signature'])]
class IPGPaymentController extends BaseController
{

    #[Post('inquiry', name: 'inquiry')]
    public function inquiry(Request $request, InboundService $inboundService, PaymentService $service)
    {
        /** @var User $user */
        $user = auth()->user();
        // save inbound
        $inboundService->create($request, 'inquiry');

        // inquiry
        $payload = $service->inquiry(user: $user, inputs: $request->input());
        return $this->response(data: $payload);
    }


    #[Post('payment', name: 'payment')]
    public function payment(Request $request, InboundService $inboundService, PaymentService $service)
    {
        /** @var User $user */
        $user = auth()->user();
        // save inbound
        $inboundService->create($request, 'payment');

        // inquiry
        $payload = $service->payment(user: $user, inputs: $request->input());
        return $this->response(data: $payload);
    }
}
