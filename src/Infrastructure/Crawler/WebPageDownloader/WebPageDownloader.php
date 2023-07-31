<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Crawler\WebPageDownloader;

use Crawler\Domain\Model\Link;

class WebPageDownloader implements WebPageDownloaderInterface
{
    public function download(Link $link): string
    {
        return (string) \file_get_contents($link->getURL());
    }
}
