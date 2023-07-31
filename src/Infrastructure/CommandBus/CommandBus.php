<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\CommandBus;

use Crawler\Domain\Command\DownloadWebPageCommand\DownloadWebPageCommand;
use Crawler\Domain\Command\DownloadWebPageCommand\DownloadWebPageCommandHandler;
use Crawler\Domain\Command\FindLinksOnWebPageCommand\FindLinksOnPageCommand;
use Crawler\Domain\Command\FindLinksOnWebPageCommand\FindLinksOnPageCommandHandler;
use Crawler\Infrastructure\DependencyInjection\ContainerInterface;
use League\Tactician\Setup\QuickStart;

class CommandBus implements CommandBusInterface
{
    private \League\Tactician\CommandBus $commandBus;

    public function __construct(ContainerInterface $container)
    {
        $this->commandBus = QuickStart::create(
            [
                DownloadWebPageCommand::class => $container->get(DownloadWebPageCommandHandler::class),
                FindLinksOnPageCommand::class => $container->get(FindLinksOnPageCommandHandler::class),
            ]
        );
    }

    public function handle(object $command): object
    {
        return $this->commandBus->handle($command);
    }
}
