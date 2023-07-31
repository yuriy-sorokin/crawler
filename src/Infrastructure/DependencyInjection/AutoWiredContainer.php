<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\DependencyInjection;

use Crawler\Infrastructure\CommandBus\CommandBus;
use Crawler\Infrastructure\CommandBus\CommandBusInterface;
use Crawler\Infrastructure\ConcurrencyCounter\FileSystemConcurrencyCounter;
use Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface;
use Crawler\Infrastructure\ConcurrencyLock\FileSystemConcurrencyLock;
use Crawler\Infrastructure\Crawler\WebPageDownloader\WebPageDownloader;
use Crawler\Infrastructure\Crawler\WebPageDownloader\WebPageDownloaderInterface;
use Crawler\Infrastructure\Logger\FileSystemLogger;
use Crawler\Infrastructure\Logger\LoggerInterface;
use Crawler\Infrastructure\Storage\WebPageStorage\FileSystemWebPageStorage;
use Crawler\Infrastructure\Storage\WebPageStorage\WebPageStorageInterface;
use League\Container\Container;
use League\Container\ReflectionContainer;

class AutoWiredContainer implements ContainerInterface
{
    private Container $container;

    public function __construct(string $baseDir, int $maxParallelProcesses, int $maxPagesToDownload)
    {
        $concurrencyLock = new FileSystemConcurrencyLock(\sprintf('%sconcurrency_lock', $baseDir));
        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());
        $this->container->addShared(ContainerInterface::class, $this);
        $this->container->addShared(WebPageDownloaderInterface::class, new WebPageDownloader());
        $this->container->addShared(WebPageStorageInterface::class, new FileSystemWebPageStorage(\sprintf('%sstorage', $baseDir)));
        $this->container->addShared(ConcurrencyLockInterface::class, $concurrencyLock);
        $this->container->addShared(LoggerInterface::class, new FileSystemLogger(\sprintf('%slog/log.log', $baseDir)));
        $this->container->addShared(
            'downloaded_web_pages_counter',
            new FileSystemConcurrencyCounter(
                $maxPagesToDownload,
                \sprintf('%sconcurrency_counter%sdownloaded_web_pages_counter.txt', $baseDir, DIRECTORY_SEPARATOR),
                'downloaded_web_pages',
                $concurrencyLock
            )
        );
        $this->container->addShared(
            'parallel_processes_counter',
            new FileSystemConcurrencyCounter(
                $maxParallelProcesses,
                \sprintf('%sconcurrency_counter%sparallel_processes_counter.txt', $baseDir, DIRECTORY_SEPARATOR),
                'parallel_processes_counter',
                $concurrencyLock
            )
        );
        $this->container->addShared(CommandBusInterface::class, $this->container->get(CommandBus::class));
    }

    public function get($className): object
    {
        return $this->container->get($className);
    }
}
