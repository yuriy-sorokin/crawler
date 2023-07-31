<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\DependencyInjection;

interface ContainerInterface
{
    public function get($className): object;
}
