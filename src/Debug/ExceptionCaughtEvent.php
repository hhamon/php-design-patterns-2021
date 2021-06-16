<?php

declare(strict_types=1);

namespace App\Debug;

final class ExceptionCaughtEvent
{
    private ErrorHandler $errorHandler;
    private \Throwable $exception;

    public function __construct(ErrorHandler $errorHandler, \Throwable $exception)
    {
        $this->errorHandler = $errorHandler;
        $this->exception    = $exception;
    }

    public function getErrorHandler(): ErrorHandler
    {
        return $this->errorHandler;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }
}