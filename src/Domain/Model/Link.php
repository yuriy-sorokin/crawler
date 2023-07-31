<?php
declare(strict_types=1);

namespace Crawler\Domain\Model;

class Link
{
    private string $URL;

    public function __construct(string $URL)
    {
        $this->URL = $URL;
    }

    public function getURL(): string
    {
        return $this->URL;
    }
}
