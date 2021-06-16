<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ExceptionCaughtEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class StandardOutputEchoProcessor implements EventSubscriberInterface
{
    public function __invoke(ExceptionCaughtEvent $event): void
    {
        if ('cli' === \PHP_SAPI) {
            return;
        }

        $exception = $event->getException();

        echo \sprintf('Error: %s', $exception->getMessage());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionCaughtEvent::class => '__invoke',
        ];
    }
}