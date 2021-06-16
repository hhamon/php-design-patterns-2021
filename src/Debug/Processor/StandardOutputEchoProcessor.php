<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ErrorHandler;

final class StandardOutputEchoProcessor implements ErrorProcessorInterface
{
    public function process(ErrorHandler $errorHandler, \Throwable $exception): void
    {
        if ('cli' === \PHP_SAPI) {
            return;
        }

        echo \sprintf('Error: %s', $exception->getMessage());
    }
}