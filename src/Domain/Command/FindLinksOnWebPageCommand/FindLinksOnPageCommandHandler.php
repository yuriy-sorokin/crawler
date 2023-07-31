<?php
declare(strict_types=1);

namespace Crawler\Domain\Command\FindLinksOnWebPageCommand;

use Crawler\Domain\Model\Link;
use Crawler\Infrastructure\Crawler\WebPageLinksFetcher\WebPageLinksFetcher;

class FindLinksOnPageCommandHandler
{
    private WebPageLinksFetcher $webPageLinksFetcher;

    public function __construct(WebPageLinksFetcher $webPageLinksFetcher)
    {
        $this->webPageLinksFetcher = $webPageLinksFetcher;
    }

    public function handle(FindLinksOnPageCommand $command): FindLinksOnPageCommandResult
    {
        $parsedLinks = $this->webPageLinksFetcher->fetch($command->getWebPage());
        $links = [];

        foreach ($parsedLinks as $parsedLink) {
            $link = new Link((string) $parsedLink->getAttribute('href'));
            $links[$link->getURL()] = $link;
        }

        return new FindLinksOnPageCommandResult(...\array_values($links));
    }
}
