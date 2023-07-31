<?php
declare(strict_types=1);

use Crawler\Infrastructure\ConcurrencyLock\AlreadyLockedException;

require_once __DIR__.'/bootstrap.php';

$container = new \Crawler\Infrastructure\DependencyInjection\AutoWiredContainer(
    \sprintf('%s%s..%s', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),
    (int) $cliArguments[ARGUMENT_MAX_PROCESSES],
    (int) $cliArguments[ARGUMENT_MAX_PAGES]
);

$url = \urldecode($cliArguments[ARGUMENT_URL]);

/** @var \Crawler\Infrastructure\CommandBus\CommandBusInterface $commandBus */
$commandBus = $container->get(\Crawler\Infrastructure\CommandBus\CommandBusInterface::class);

/** @var \Crawler\Domain\Command\DownloadWebPageCommand\DownloadWebPageCommandResult $downloadWebPageResult */
$downloadWebPageResult = $commandBus->handle(
    new \Crawler\Domain\Command\DownloadWebPageCommand\DownloadWebPageCommand(
        new \Crawler\Domain\Model\Link($url)
    )
);

/** @var \Crawler\Infrastructure\ConcurrencyCounter\ConcurrencyCounterInterface $parallelProcessesCounter */
$parallelProcessesCounter = $container->get('parallel_processes_counter');
$parallelProcessesCounter->decreaseCount();

/** @var \Crawler\Domain\Command\FindLinksOnWebPageCommand\FindLinksOnPageCommandResult $findLinksResult */
$findLinksResult = $commandBus->handle(
    new \Crawler\Domain\Command\FindLinksOnWebPageCommand\FindLinksOnPageCommand(
        $downloadWebPageResult->getWebPage()
    )
);

/** @var \Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface $concurrencyWebPageLock */
$concurrencyWebPageLock = $container->get(\Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface::class);

/** @var \Crawler\Infrastructure\ConcurrencyCounter\ConcurrencyCounterInterface $downloadedWebPagesCounter */
$downloadedWebPagesCounter = $container->get('downloaded_web_pages_counter');

foreach ($findLinksResult->getLinks() as $link) {
    try {
        $concurrencyWebPageLock->acquireLock($link->getURL());
    } catch (AlreadyLockedException $exception) {
        continue;
    }

    try {
        $downloadedWebPagesCounter->increaseCount();
    } catch (\Crawler\Infrastructure\ConcurrencyCounter\CountExceededException $exception) {
        return;
    }

    $canLaunchNewProcess = false;

    while (false === $canLaunchNewProcess) {
        try {
            $parallelProcessesCounter->increaseCount();

            $canLaunchNewProcess = true;
        } catch (\Crawler\Infrastructure\ConcurrencyCounter\CountExceededException $exception) {
            \sleep(1);
        }
    }

    \exec(
        \sprintf(
            'php %s%scrawler.php --%s=%s --%s=%s --%s=%s > /dev/null 2>&1 &',
            __DIR__,
            DIRECTORY_SEPARATOR,
            ARGUMENT_URL,
            \urlencode($link->getURL()),
            ARGUMENT_MAX_PROCESSES,
            $cliArguments[ARGUMENT_MAX_PROCESSES],
            ARGUMENT_MAX_PAGES,
            $cliArguments[ARGUMENT_MAX_PAGES]
        )
    );
}
