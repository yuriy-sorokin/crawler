<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Crawler\WebPageDownloader;

use Crawler\Domain\Model\Link;
use Crawler\Domain\Model\WebPage;

interface WebPageDownloaderInterface
{
    public function download(Link $link): string;
}
