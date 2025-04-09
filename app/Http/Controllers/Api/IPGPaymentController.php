<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CidException;
use App\Http\Controllers\Api\Controller as BaseController;
use App\Http\Response;
use App\Services\Inbound\InboundService;
use App\Services\PaymentService;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

#[Prefix('')]
#[Name('', true, true)]
#[Middleware(['ipg_validate_client_signature'])]
class IPGPaymentController extends BaseController
{

    /**
     * @param Request $request
     * @param InboundService $inboundService
     * @param PaymentService $service
     * @return Response
     * @throws CidException
     * @throws ValidationException
     */
    #[Post('inquiry', name: 'inquiry')]
    public function inquiry(Request $request, InboundService $inboundService, PaymentService $service): \App\Http\Response
    {
        // save inbound
        $inboundService->create($request, 'inquiry');

        // inquiry
        $payload = $service->inquiry(inputs: $request->input());
        return $this->response(data: $payload);
    }


    /**
     * @param Request $request
     * @param InboundService $inboundService
     * @param PaymentService $service
     * @return Response
     * @throws CidException
     * @throws ValidationException
     */
    #[Post('payment', name: 'payment')]
    public function payment(Request $request, InboundService $inboundService, PaymentService $service): \App\Http\Response
    {
        // save inbound
        $inboundService->create($request, 'payment');

        // inquiry
        $payload = $service->payment(inputs: $request->input());
        return $this->response(data: $payload);
    }
}
