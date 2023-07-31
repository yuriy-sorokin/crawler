<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\CommandBus;

interface CommandBusInterface
{
    public function handle(object $command): object;
}
