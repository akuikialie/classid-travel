<?php

namespace App\Services\Inbound;

use App\Concerns\ValidationInput;
use App\Models\Inbound\Inbound;
use Illuminate\Http\Request;

class InboundService
{
    use ValidationInput;

    /**
     * @param Request $request
     * @param string $action
     * @return Inbound
     */
    public function create(Request $request, string $action): Inbound
    {
        $inbound = new Inbound();
        $inbound->fill([
            'ip' => $request->getClientIp(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->url(),
            'actions' => $action,
            'headers' => $request->headers->all(),
            'params' => $request->input(),
            'body' => $request->input(),
        ]);
        $inbound->save();
        return $inbound;
    }
}
