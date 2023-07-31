<?php
declare(strict_types=1);

namespace Crawler\Domain\Model;

class WebPage
{
    private Link $link;
    private string $content;

    public function __construct(Link $link, string $content)
    {
        $this->link = $link;
        $this->content = $content;
    }

    public function getLink(): Link
    {
        return $this->link;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
