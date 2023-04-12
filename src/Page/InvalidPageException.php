<?php

namespace App\Page;

use Exception;
use Throwable;

class InvalidPageException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = "No page with the slug $message` was found";
        parent::__construct($message, $code, $previous);
    }
}