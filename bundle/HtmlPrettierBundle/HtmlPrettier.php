<?php

declare(strict_types=1);

namespace Homepage\HtmlPrettierBundle;

use Generator;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceInterface;
use Sculpin\Core\Source\SourceSet;

use function strlen;

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
        $config = [
            'output-html' => true,
            'drop-empty-elements' => false,
            'indent' => true,
            'wrap' => 120,
        ];

        $sources = $this->filterSource($event->sourceSet());

        /** @var SourceInterface $source */
        foreach ($sources as $source) {
            $html = $source->formattedContent();
            $tidy = new \tidy();
            $tidy->parseString($html, $config, 'utf8');
            $tidy->cleanRepair();
            $source->setFormattedContent((string) $tidy);
        }
    }

    private function filterSource(SourceSet $sourceSet): Generator
    {
        /** @var SourceInterface $source */
        foreach ($sourceSet->allSources() as $source) {
            $filename = $source->filename();

            $isSuitable = $this->endsWith($filename, '.md')
                || $this->endsWith($filename, '.html.twig')
            ;

            if ($isSuitable) {
                yield $source;
            }
        }
    }

    private function endsWith($haystack, $needle): bool
    {
        $length = strlen($needle);

        return $length === 0 || (substr($haystack, -$length) === $needle);
    }
}
