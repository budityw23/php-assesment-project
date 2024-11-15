<?php

namespace Exception;

use Exception as BaseException;
use Throwable;

class DatabaseException extends BaseException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}