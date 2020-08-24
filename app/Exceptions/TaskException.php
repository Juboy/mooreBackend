<?php

namespace App\Exceptions;

use Exception;

class TaskException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}
