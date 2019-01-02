<?php

namespace Homepage\SiteMapBundle;

use Sculpin\Core\DataProvider\DataProviderInterface;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceSet;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SiteMapGenerator implements DataProviderInterface, EventSubscriberInterface
{
    protected $siteMap;

    /**
     * @var SourceSet
     */
    protected $sources;

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
     * Before run.
     *
     * @param SourceSetEvent $sourceSetEvent Source Set Event
     */
    public function saveSourceSet(SourceSetEvent $sourceSetEvent)
    {
        $this->sources = $sourceSetEvent->sourceSet();
    }

    protected function buildSiteMap()
    {
        if (!empty($this->siteMap)) {
            return $this->siteMap;
        }

        $siteMap = [];

        /** @var \Sculpin\Core\Source\FileSource $source */
        foreach ($this->sources->allSources() as $source) {
            $data = $source->data()->export();

            if (empty($data) || $source->useFileReference()) {
                continue;
            }

            $siteMapData = $data['sitemap'] ?? [];

            if (isset($siteMapData['_exclude'])) {
                continue;
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

            $siteMap[$url['loc']] = $url;
        }

        $this->siteMap = $siteMap;

        return $this->siteMap;
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
}
