<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';

\shell_exec(\sprintf('rm -rf %s%s..%sstorage', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR));
\shell_exec(\sprintf('rm -rf %s%s..%sconcurrency_counter', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR));
\shell_exec(\sprintf('rm -rf %s%s..%sconcurrency_lock', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR));
\shell_exec(\sprintf('rm -rf %s%s..%slog', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR));

$container = new \Crawler\Infrastructure\DependencyInjection\AutoWiredContainer(
    \sprintf('%s%s..%s', __DIR__, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),
    (int) $cliArguments[ARGUMENT_MAX_PROCESSES],
    (int) $cliArguments[ARGUMENT_MAX_PAGES]
);

/** @var \Crawler\Infrastructure\ConcurrencyCounter\ConcurrencyCounterInterface $downloadedWebPagesCounter */
$downloadedWebPagesCounter = $container->get('downloaded_web_pages_counter');
$downloadedWebPagesCounter->increaseCount();

/** @var \Crawler\Infrastructure\ConcurrencyCounter\ConcurrencyCounterInterface $parallelProcessesCounter */
$parallelProcessesCounter = $container->get('parallel_processes_counter');
$parallelProcessesCounter->increaseCount();

/** @var \Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface $concurrencyWebPageLock */
$concurrencyWebPageLock = $container->get(\Crawler\Infrastructure\ConcurrencyLock\ConcurrencyLockInterface::class);
$concurrencyWebPageLock->acquireLock($cliArguments[ARGUMENT_URL]);

\exec(\sprintf('php %s%scrawler.php%s', __DIR__, DIRECTORY_SEPARATOR, $cliArgumentsToPass));
