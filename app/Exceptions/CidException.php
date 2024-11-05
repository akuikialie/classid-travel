<?php

namespace App\Exceptions;

use Exception;
use App\Enums\ResponseCode;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use JsonException;
use Throwable;

class CidException extends Exception implements Arrayable, Responsable
{
    /**
     * Base Exception constructor.
     *
     * @param ResponseCode $rc
     * @param ?string $message
     * @param array|null $data
     * @param Throwable|null $previous
     */
    public function __construct(
        public ResponseCode  $rc = ResponseCode::ERR_UNKNOWN,
        ?string              $message = null,
        protected array|null $data = null,
        ?Throwable           $previous = null
    )
    {
        if (is_null($message)) {
            $message = $rc->message();
        }
        parent::__construct($message, 0, $previous);
    }

    /**
     * Get response code.
     *
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->rc->name;
    }

    /**
     * Get response message.
     *
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     *
     * @throws JsonException
     * @throws BindingResolutionException
     */
    public function toResponse($request)
    {
        return $request->expectsJson()
            ? response()->json($this->toArray(), $this->rc->httpCode())
            : response()->make(json_encode($this->toArray(), JSON_THROW_ON_ERROR))
                ->withException($this);
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $carrier = [
            'rc' => $this->getResponseCode(),
            'message' => $this->getResponseMessage(),
            'timestamp' => now(),
            'payload' => $this->data,
        ];

        if (config('app.debug') && $this->getPrevious() instanceof Throwable) {
            $carrier['debug'] = [
                'origin_message' => $this->getPrevious()->getMessage(),
                'class' => get_class($this->getPrevious()),
                'file' => $this->getPrevious()->getFile(),
                'line' => $this->getPrevious()->getLine(),
                'trace' => $this->getPrevious()->getTrace(),
            ];
        }

        return $carrier;
    }
}
