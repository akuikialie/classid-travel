<?php

namespace App\Core\Logging;

use Illuminate\Support\Arr;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(function ($record) {
                return Arr::add($record, 'prefix', app("request_id"));
            });
            $handler->pushProcessor(function ($record) {
                return Arr::add($record, 'url', \Request::url());
            });
            $handler->setFormatter(tap(new LineFormatter(
                "[%datetime%] %prefix%.%channel%.%level_name%: [%url%] %message% %context% %extra%\n",
                'Y-m-d H:i:s',
                true,
                true
            ), function ($formatter) {
                $formatter->includeStacktraces();
            }));
        }
    }
}
