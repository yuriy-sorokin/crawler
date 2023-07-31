<?php
declare(strict_types=1);

namespace Crawler\Domain\Command\DownloadWebPageCommand;

use Crawler\Domain\Model\WebPage;
use Crawler\Infrastructure\Crawler\WebPageDownloader\WebPageDownloaderInterface;
use Crawler\Infrastructure\Logger\LoggerInterface;
use Crawler\Infrastructure\Storage\WebPageStorage\WebPageStorageInterface;

class DownloadWebPageCommandHandler
{
    private WebPageDownloaderInterface $webPageDownloader;
    private WebPageStorageInterface $webPageStorage;
    private LoggerInterface $logger;

    public function __construct(WebPageDownloaderInterface $webPageDownloader, WebPageStorageInterface $webPageStorage, LoggerInterface $logger)
    {
        $this->webPageDownloader = $webPageDownloader;
        $this->webPageStorage = $webPageStorage;
        $this->logger = $logger;
    }

    public function handle(DownloadWebPageCommand $command): DownloadWebPageCommandResult
    {
        $webPage = new WebPage(
            $command->getLink(),
            $this->webPageDownloader->download($command->getLink())
        );

        $this->webPageStorage->store($webPage);

        $this->logger->info('Downloaded page', ['url' => $command->getLink()->getURL()]);

        return new DownloadWebPageCommandResult($webPage);
    }
}
