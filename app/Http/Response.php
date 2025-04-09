<?php

namespace App\Http;

use App\Enums\ResponseCode;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use JsonException;

class Response implements Responsable
{
    /**
     * Response constructor.
     *
     * @param ResponseCode $code
     * @param Arrayable<int|string, mixed>|array<int|string, mixed>|null $data
     * @param string|null $message
     */
    public function __construct(
        protected JsonResource|ResourceCollection|Arrayable|LengthAwarePaginator|CursorPaginator|array|null $data = null,
        protected ?string                                                                                   $message = null,
        protected ResponseCode                                                                              $code = ResponseCode::SUCCESS,
    )
    {
    }

    /**
     * @return JsonResource|ResourceCollection|Arrayable|LengthAwarePaginator|CursorPaginator|array|null
     */
    public function getData(): JsonResource|ResourceCollection|Arrayable|LengthAwarePaginator|CursorPaginator|array|null
    {
        return $this->data instanceof Arrayable ? $this->data->toArray() : $this->data;
    }

    /**
     * Get response message.
     *
     * @return string|null
     */
    public function getMessage(): string|null
    {
        return $this->message ?? $this->code->message();
    }

    /**
     * Get response data.
     *
     * @return array<string, mixed>
     */
    public function getResponseData(): array
    {
        $resp = [
            'rc' => $this->code->name,
            'message' => $this->getMessage(),
            'timestamp' => now(),
        ];

        if ($this->data instanceof Paginator) {
            return array_merge($resp, ['payload' => $this->data->toArray()]);
        }

        if ($this->data instanceof Arrayable) {
            return array_merge($resp, ['payload' => [JsonResource::$wrap => $this->data->toArray()]]);
        }

        if (($this->data?->resource ?? null) instanceof AbstractPaginator) {
            return array_merge($resp, [
                'payload' => array_merge(
                    $this->data->resource->toArray(),
                    [JsonResource::$wrap => $this->getData()]
                )
            ]);
        }

        if (is_array($this->data)){
            return array_merge($resp, [JsonResource::$wrap => $this->data]);
        }

        return array_merge($resp, [
            'payload' => is_null($this->data) ? $this->data : [JsonResource::$wrap => $this->data]
        ]);

        /**
         * this part is not supported for laravel resource and resource collection
         */
//        $resp = [
//            'rc' => $this->code->name,
//            'message' => $this->getMessage(),
//            'timestamp' => now(),
//        ];
//
//        if ($this->data instanceof Paginator || $this->data instanceof CursorPaginator) {
//            $paginatorPayload = $this->data->toArray();
//
//            return array_merge(
//                $resp,
//                Arr::except($paginatorPayload, ['data']),
//                ['payload' => $paginatorPayload['data']],
//            );
//        }
//
//        if ($this->data instanceof Arrayable) {
//            return array_merge($resp, ['payload' => $this->data->toArray()]);
//        }
//
//        return array_merge($resp, ['payload' => $this->data]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws JsonException
     */
    public function toResponse($request): \Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            return response()->json($this->getResponseData(), $this->code->httpCode());
        }

        return new \Illuminate\Http\Response(json_encode($this->getResponseData(), JSON_THROW_ON_ERROR), $this->code->httpCode());
    }
}
