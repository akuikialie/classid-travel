<?php

namespace App\Exceptions;

use Exception;

class HandleCatchableException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @return static
     */
    public static function catchable(string $message, int $code = 900): static
    {
        return new static(message: "{$message}", code: $code);
    }
}
