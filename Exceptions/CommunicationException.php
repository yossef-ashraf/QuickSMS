<?php

namespace Fabrikar\Communication\Exceptions;

use Exception;
use Throwable;

class CommunicationException extends Exception
{
    protected $context;

    public function __construct(
        string $message = "", 
        int $code = 0, 
        ?Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}