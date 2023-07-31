<?php
declare(strict_types=1);

namespace Crawler\Domain\Command\DownloadWebPageCommand;

use Crawler\Domain\Model\Link;

class DownloadWebPageCommand
{
    private Link $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function getLink(): Link
    {
        return $this->link;
    }
}
