<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\Crawler\WebPageLinksFetcher;

use Crawler\Domain\Model\WebPage;

class WebPageLinksFetcher
{
    public function fetch(WebPage $webPage): \DOMNodeList
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($webPage->getContent(), LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);

        $parsedUrl = \parse_url($webPage->getLink()->getURL());

        return $xpath->query(\sprintf('//a[contains(@href, "%s")]', $parsedUrl['host']));
    }
}
