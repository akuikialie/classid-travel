<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller as BaseController;
use App\Models\User;
use App\Services\PaymentService;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;

#[Prefix('')]
#[Name('', true, true)]
class IPGPaymentController extends BaseController
{

    #[Post('inquiry', name: 'inquiry')]
    public function inquiry(Request $request, PaymentService $service)
    {
        /** @var User $user */
        $user = auth()->user();
        // save inbound

        // inquiry
        $payload = $service->inquiry(user: $user, inputs: $request->input());
        return $this->response(data: $payload);
    }


    #[Post('payment', name: 'payment')]
    public function payment(Request $request, PaymentService $service)
    {
        /** @var User $user */
        $user = auth()->user();
        // save inbound

        // inquiry
        $payload = $service->payment(user: $user, inputs: $request->input());
        return $this->response(data: $payload);
    }
}
