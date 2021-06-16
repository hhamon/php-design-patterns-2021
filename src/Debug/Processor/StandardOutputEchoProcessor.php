<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ExceptionCaughtEvent;

final class StandardOutputEchoProcessor
{
    public function __invoke(ExceptionCaughtEvent $event): void
    {
        if ('cli' === \PHP_SAPI) {
            return;
        }

        $exception = $event->getException();

        echo \sprintf('Error: %s', $exception->getMessage());
    }
}