<?php

namespace App\Debug;

use App\Debug\Processor\ErrorProcessorInterface;

final class ErrorHandler
{
    private static ?self $instance = null;

    private int $errorLevel;
    private ?int $previousErrorLevel = null;
    private bool $registered = false;

    private array $errors = [];
    private array $exceptions = [];

    /** @var array ErrorProcessorInterface[] */
    private array $processors = [];

    public static function create(int $errorLevel = E_ALL): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new self($errorLevel);

        return self::$instance;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            throw new \LogicException('Instance has not been created yet. Use ::create() method first.');
        }

        return self::$instance;
    }

    public function addProcessor(ErrorProcessorInterface $processor): self
    {
        if (! \in_array($processor, $this->processors, true)) {
            $this->processors[] = $processor;
        }

        return $this;
    }

    private function __construct(int $errorLevel)
    {
        $this->errorLevel = $errorLevel;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \BadMethodCallException('Object deserialization is not allowed.');
    }

    public function handleError(int $number, string $error, string $file, int $line): void
    {
        $this->errors[] = [
            'number' => $number,
            'error' => $error,
            'file' => $file,
            'line' => $line,
            'ts' => time(),
        ];

        $this->handleException(new \ErrorException($error, $number, 1, $file, $line));
    }

    public function handleException(\Throwable $e): void
    {
        $this->exceptions[] = [
            'exception' => $e,
            'ts' => time(),
        ];

        foreach ($this->processors as $processor) {
            $processor->process($this, $e);
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function reset(): self
    {
        $this->errors     = [];
        $this->exceptions = [];

        return $this;
    }

    public function register(): self
    {
        if (!$this->registered) {
            $this->previousErrorLevel = error_reporting($this->errorLevel);
            set_error_handler([$this, 'handleError'], $this->errorLevel);
            set_exception_handler([$this, 'handleException']);
            $this->registered = true;
        }

        return $this;
    }

    public function unregister(): self
    {
        if ($this->registered) {
            error_reporting($this->previousErrorLevel);
            restore_error_handler();
            restore_exception_handler();
            $this->registered = false;
            $this->previousErrorLevel = null;
        }

        return $this;
    }
}
