<?php
declare(strict_types=1);

namespace Crawler\Domain\Command\FindLinksOnWebPageCommand;

use Crawler\Domain\Model\WebPage;

class FindLinksOnPageCommand
{
    private WebPage $webPage;

    public function __construct(WebPage $webPage)
    {
        $this->webPage = $webPage;
    }

    public function getWebPage(): WebPage
    {
        return $this->webPage;
    }
}
