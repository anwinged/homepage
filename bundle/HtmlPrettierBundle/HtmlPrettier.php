<?php

namespace Homepage\HtmlPrettierBundle;

use Gajus\Dindent\Indenter;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceSet;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HtmlPrettier implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Sculpin::EVENT_AFTER_FORMAT => ['formatHtml', -100],
        ];
    }

    public function formatHtml(SourceSetEvent $event): void
    {
        $indenter = new Indenter([
            'indentation_character' => '  ',
        ]);

        $sources = $this->filterSource($event->sourceSet());

        /** @var \Sculpin\Core\Source\SourceInterface $source */
        foreach ($sources as $source) {
            $html = $source->formattedContent();
            $formatted = $indenter->indent($html);
            $source->setFormattedContent($formatted);
        }
    }

    private function filterSource(SourceSet $sourceSet): \Generator
    {
        /** @var \Sculpin\Core\Source\SourceInterface $source */
        foreach ($sourceSet->allSources() as $source) {
            $filename = $source->filename();

            $isSuitable = $filename === 'sitemap.xml'
                || $this->endsWith($filename, '.md')
                || $this->endsWith($filename, '.html.twig')
            ;

            if ($isSuitable) {
                yield $source;
            }
        }
    }

    private function endsWith($haystack, $needle): bool
    {
        $length = \strlen($needle);

        return $length === 0 || (substr($haystack, -$length) === $needle);
    }
}
