<?php

namespace App\Tests\Debug;

use App\Debug\ErrorHandler;
use App\Debug\ExceptionCaughtEvent;
use App\Debug\Processor\EmailProcessor;
use App\Debug\Processor\StandardOutputEchoProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class ErrorHandlerTest extends TestCase
{
    protected function tearDown(): void
    {
        ErrorHandler::getInstance()
            ->reset()
            ->unregister();
    }

    public function testCaptureExceptionAndProcessIt(): void
    {
        $spy006 = new class {
            public bool $invoked = false;
            public function __invoke(ExceptionCaughtEvent $event): void
            {
                $errorHandler = $event->getErrorHandler();
                $exception = $event->getException();

                $this->invoked = true;
            }
        };

        $spy007 = $this->createMock(EmailProcessor::class);
        $spy007->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ExceptionCaughtEvent::class));

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(ExceptionCaughtEvent::class, new StandardOutputEchoProcessor());
        $dispatcher->addListener(ExceptionCaughtEvent::class, $spy006);
        $dispatcher->addListener(ExceptionCaughtEvent::class, $spy007);

        ErrorHandler::create()
            ->setDispatcher($dispatcher)
            ->register();

        $this->assertFalse($spy006->invoked);

        \trigger_error('Foo', E_USER_ERROR);

        $this->assertTrue($spy006->invoked);
    }

    public function testCaptureError(): void
    {
        $handler = ErrorHandler::create()->register();

        \trigger_error('Fatal Error', E_USER_ERROR);

        $this->assertCount(1, $handler->getErrors());
    }

    public function testErrorHandlerIsSingleton(): void
    {
        $handler1 = ErrorHandler::create();
        $handler2 = ErrorHandler::create();

        $this->assertSame($handler1, $handler2);
    }
}
