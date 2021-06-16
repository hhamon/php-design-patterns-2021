<?php

declare(strict_types=1);

namespace App\Debug\Processor;

use App\Debug\ExceptionCaughtEvent;

class EmailProcessor
{
    public function __invoke(ExceptionCaughtEvent $event): void
    {
        echo "Email has been sent!\n";
    }
}