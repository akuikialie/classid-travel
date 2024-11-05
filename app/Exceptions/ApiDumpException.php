<?php

namespace App\Exceptions;

use Exception;

class ApiDumpException extends Exception
{
    public function __construct(public mixed $data)
    {
    }

    #Post
    public function render()
    {
        return response()->json([$this->data]);
    }
}
