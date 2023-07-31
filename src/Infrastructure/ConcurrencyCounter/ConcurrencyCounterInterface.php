<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\ConcurrencyCounter;

interface ConcurrencyCounterInterface
{
    /**
     * @throws CountExceededException
     */
    public function increaseCount(): void;
    public function decreaseCount(): void;
    public function getCount(): int;
}
