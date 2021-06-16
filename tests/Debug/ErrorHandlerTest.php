<?php

namespace App\Tests\Debug;

use App\Debug\ErrorHandler;
use App\Debug\Processor\ErrorProcessorInterface;
use App\Debug\Processor\StandardOutputEchoProcessor;
use PHPUnit\Framework\TestCase;

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
        $spy006 = new class () implements ErrorProcessorInterface {
            public bool $invoked = false;
            public function process(ErrorHandler $errorHandler, \Throwable $exception): void
            {
                $this->invoked = true;
            }
        };

        $spy007 = $this->createMock(ErrorProcessorInterface::class);
        $spy007->expects($this->once())->method('process');

        ErrorHandler::create()
            ->addProcessor(new StandardOutputEchoProcessor())
            ->addProcessor($spy006)
            ->addProcessor($spy007)
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
