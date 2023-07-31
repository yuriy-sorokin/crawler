<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Storage\WebPageStorage;

use Crawler\Domain\Model\WebPage;

class FileSystemWebPageStorage implements WebPageStorageInterface
{
    private string $storageDirectory;

    public function __construct(string $storageDirectory)
    {
        $this->storageDirectory = $storageDirectory;

        if (false === \is_dir($this->storageDirectory)) {
            \mkdir($this->storageDirectory);
        }
    }

    public function store(WebPage $webPage): void
    {
        $fileName = \tempnam($this->storageDirectory, '');
        \file_put_contents(\sprintf('%s.html', $fileName), $webPage->getContent());
        \unlink($fileName);
    }
}
