<?php
declare(strict_types=1);

namespace Crawler\Domain\Command\FindLinksOnWebPageCommand;

use Crawler\Domain\Model\Link;

class FindLinksOnPageCommandResult
{
    /**
     * @var Link[]
     */
    private array $links;

    public function __construct(Link ...$links)
    {
        $this->links = $links;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}
