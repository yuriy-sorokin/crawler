<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Logger;

interface LoggerInterface
{
    public function info(string $message, array $context): void;
}
