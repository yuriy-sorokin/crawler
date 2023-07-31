<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Storage\WebPageStorage;

use Crawler\Domain\Model\WebPage;

interface WebPageStorageInterface
{
    public function store(WebPage $webPage): void;
}
