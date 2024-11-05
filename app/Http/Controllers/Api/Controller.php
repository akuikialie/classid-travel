<?php

namespace App\Http\Controllers\Api;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected array $responseMessages;

    /**
     * Use to get response message
     *
     * @param string $context
     * @return string
     */
    public function getResponseMessage(string $context): string
    {
        return $this->responseMessages[$context];
    }

    /**
     * @param \Illuminate\Contracts\Support\Arrayable<int|string, mixed>|\Illuminate\Pagination\LengthAwarePaginator<\Illuminate\Database\Eloquent\Model>|\Illuminate\Pagination\CursorPaginator<\Illuminate\Database\Eloquent\Model>|array<int|string, mixed>|null $data
     * @param \App\Enums\ResponseCode $rc
     * @param string|null $message
     * @return \App\Http\Response
     */
    public function response(
        JsonResource|ResourceCollection|Arrayable|LengthAwarePaginator|CursorPaginator|array|null $data = null,
        ?string                                                                                    $message = null,
        ResponseCode                                                                              $rc = ResponseCode::SUCCESS,
    ): Response
    {
        return new Response($data, $message, $rc);
    }
}
