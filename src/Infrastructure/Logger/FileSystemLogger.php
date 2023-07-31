<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Logger;

class FileSystemLogger implements LoggerInterface
{
    private string $logFilePath;

    public function __construct(string $logFilePath)
    {
        $this->logFilePath = $logFilePath;

        $logDirectory = \dirname($this->logFilePath);

        if (false === \is_dir($logDirectory)) {
            \mkdir($logDirectory);
        }
    }

    public function info(string $message, array $context): void
    {
        \file_put_contents(
            $this->logFilePath,
            \json_encode(
                [
                    'message' => $message,
                    'context' => $context,
                ]
            ).PHP_EOL,
            FILE_APPEND
        );
    }
}
