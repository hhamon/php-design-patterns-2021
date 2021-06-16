<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ErrorHandler;

interface ErrorProcessorInterface
{
    public function process(ErrorHandler $errorHandler, \Throwable $exception): void;
}