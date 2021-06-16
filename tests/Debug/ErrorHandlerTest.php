<?php

namespace App\Tests\Debug;

use App\Debug\ErrorHandler;
use PHPUnit\Framework\TestCase;

final class ErrorHandlerTest extends TestCase
{
    protected function tearDown(): void
    {
        ErrorHandler::getInstance()->unregister();
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
