<?php

namespace App\Exceptions;

use App\Enums\ResponseCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $e): Response|JsonResponse|SymfonyResponse
    {
        if ((false === $e instanceof CidException) && $request->expectsJson()) {
            $e = $this->mapToCidException($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            $prefix = request()->route()->getPrefix();
            if ($prefix =='admin') {
                return redirect(route('admin.login'));
            }
            return redirect(route('login'));
        }

        return parent::render($request, $e);
    }

    public function report(Throwable $e)
    {
        if ($this->shouldReport($e) && app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    private function mapToCidException(Request $request, Throwable $e): CidException|Throwable
    {
        if ($e instanceof ModelNotFoundException) {
            return new CidException(ResponseCode::ERR_ENTITY_NOT_FOUND, ResponseCode::ERR_ENTITY_NOT_FOUND->message(), previous: $e);
        }

        if ($e instanceof ValidationException) {
            return new CidException(ResponseCode::ERR_VALIDATION, $e->getMessage(), $e->errors(), previous: $e);
        }

        if ($e instanceof RoleAlreadyExists) {
            return new CidException(ResponseCode::ERR_VALIDATION, $e->getMessage(), previous: $e);
        }

//        if ($e instanceof OAuthServerException || $e instanceof AuthenticationException) {
//            return new CidException(ResponseCode::ERR_AUTHENTICATION, $e->getMessage(), null, $e);
//        }

        if ($e instanceof AuthenticationException) {
            return new CidException(ResponseCode::ERR_AUTHENTICATION, $e->getMessage(), null, $e);
        }

        if ($e instanceof NotFoundHttpException) {
            return new CidException(ResponseCode::ERR_ROUTE_NOT_FOUND, $e->getMessage(), null, $e);
        }

        if ($e instanceof UniqueConstraintViolationException) {
            return new CidException(ResponseCode::ERR_UNIQUE_RECORD, __('Unique Records Violation in Table.'), null, $e);
        }

        if ($e instanceof AuthorizationException || $e instanceof UnauthorizedException) {
            return new CidException(ResponseCode::ERR_ACTION_UNAUTHORIZED, $e->getMessage(), null, $e);
        }

        if ($e instanceof QueryException) {
            $message = $e->getMessage();
            if (str($message)->contains('Foreign key violation')) {
                return new CidException(ResponseCode::ERR_RECORD_CONSTRAINT, __('Record Probably in use'), null, $e);
            }
            return new CidException(ResponseCode::ERR_QUERY_EXCEPTION, __('contact_developer'), null, $e);
        }

        if (config('app.debug')) {
            return $e;
        }

        return new CidException(
            rc: ResponseCode::ERR_UNKNOWN,
            data: [
                'base_url' => $request->getBaseUrl(),
                'path' => $request->getUri(),
                'origin' => $request->ip(),
                'method' => $request->getMethod(),
            ],
            previous: $e
        );
    }
}
