<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\ConcurrencyCounter;

use Crawler\Infrastructure\ConcurrencyLock\AlreadyLockedException;
use Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface;

class FileSystemConcurrencyCounter implements ConcurrencyCounterInterface
{
    private int $maxCounter;
    private string $counterFilePath;
    private string $concurrencyKey;
    private ConcurrencyLockInterface $concurrencyLock;

    public function __construct(int $maxCounter, string $counterFilePath, string $concurrencyKey, ConcurrencyLockInterface $concurrencyLock)
    {
        $this->maxCounter = $maxCounter;
        $this->counterFilePath = $counterFilePath;
        $this->concurrencyKey = $concurrencyKey;
        $this->concurrencyLock = $concurrencyLock;

        $counterFileDirectory = \dirname($this->counterFilePath);

        if (false === \is_dir($counterFileDirectory)) {
            \mkdir($counterFileDirectory);
        }

        if (false === \is_file($this->counterFilePath)) {
            \file_put_contents($this->counterFilePath, 0);
        }
    }

    public function increaseCount(): void
    {
        $this->waitForLockAcquire();
        $count = $this->getCount();

        if ($count >= $this->maxCounter) {
            $this->concurrencyLock->releaseLock($this->concurrencyKey);

            throw new CountExceededException();
        }

        \file_put_contents($this->counterFilePath, $count + 1);

        $this->concurrencyLock->releaseLock($this->concurrencyKey);
    }

    public function decreaseCount(): void
    {
        $this->waitForLockAcquire();
        \file_put_contents($this->counterFilePath, $this->getCount() - 1);
        $this->concurrencyLock->releaseLock($this->concurrencyKey);
    }

    public function getCount(): int
    {
        return (int) \file_get_contents($this->counterFilePath);
    }

    private function waitForLockAcquire(): void
    {
        while (false) {
            try {
                $this->concurrencyLock->acquireLock($this->concurrencyKey);

                return;
            } catch (AlreadyLockedException $exception) {
            }
        }
    }
}
