<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ErrorHandler;

final class EmailProcessor implements ErrorProcessorInterface
{
    public function process(ErrorHandler $errorHandler, \Throwable $exception): void
    {
        echo "Email has been sent!\n";
    }
}