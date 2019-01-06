<?php

namespace Homepage\SiteMapBundle;

use Sculpin\Core\DataProvider\DataProviderInterface;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceInterface;
use Sculpin\Core\Source\SourceSet;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SiteMapGenerator implements DataProviderInterface, EventSubscriberInterface
{
    /**
     * @var array|null
     */
    private $siteMap;

    /**
     * @var SourceSet
     */
    private $sources;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Sculpin::EVENT_BEFORE_RUN => 'saveSourceSet',
        ];
    }

    /**
     * Provide data.
     *
     * @return array
     */
    public function provideData(): array
    {
        $this->buildSiteMap();

        return $this->siteMap;
    }

    /**
     * Before run.
     *
     * @param SourceSetEvent $sourceSetEvent Source Set Event
     */
    public function saveSourceSet(SourceSetEvent $sourceSetEvent)
    {
        $this->sources = $sourceSetEvent->sourceSet();
    }

    protected function buildSiteMap(): array
    {
        if ($this->siteMap !== null) {
            return $this->siteMap;
        }

        $this->siteMap = $this->createSiteMap();

        return $this->siteMap;
    }

    private function createSiteMap(): array
    {
        $siteMap = [];

        /** @var \Sculpin\Core\Source\FileSource $source */
        foreach ($this->sources->allSources() as $source) {
            $url = $this->createSiteUrlFromSource($source);
            if (!$url) {
                continue;
            }
            $siteMap[$url['loc']] = $url;
        }

        return $siteMap;
    }

    private function createSiteUrlFromSource(SourceInterface $source): array
    {
        $data = $source->data()->export();

        if (empty($data) || $source->useFileReference()) {
            return [];
        }

        if ($data['draft']) {
            return [];
        }

        $siteMapData = $data['sitemap'] ?? [];

        if (array_key_exists('_exclude', $siteMapData)) {
            return [];
        }

        $loc = $data['canonical'] ?? $data['url'];

        if (is_callable([$source, 'file'])) {
            $lastmod = date(DATE_W3C, $source->file()->getMTime());
        } else {
            $lastmod = date(DATE_W3C);
        }

        $url = [
            'loc' => $loc,
            'lastmod' => $lastmod,
        ];

        if (isset($data['sitemap'])) {
            $url = array_merge($url, $data['sitemap']);
        }

        return $url;
    }
}
