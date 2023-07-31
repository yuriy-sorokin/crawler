<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\ConcurrencyLock;

use Crawler\Domain\Model\Link;
use Crawler\Infrastructure\ConcurrencyLock\AlreadyLockedException;

interface ConcurrencyLockInterface
{
    /**
     * @param string $key
     * @throws AlreadyLockedException
     */
    public function acquireLock(string $key): void;

    public function releaseLock(string $key): void;
}
