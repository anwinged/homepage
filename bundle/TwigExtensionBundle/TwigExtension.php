<?php

namespace Homepage\TwigExtensionBundle;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    private $outputDir;

    public function __construct(string $outputDir)
    {
        $this->outputDir = $outputDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('hashed_asset', [$this, 'createHashedFileLink']),
        ];
    }

    public function createHashedFileLink(string $path): string
    {
        $fullPath = $this->join($this->outputDir, $path);
        $realPath = realpath($fullPath);

        if (!file_exists($realPath)) {
            return sprintf('%s?v=%s', $path, time());
        }

        $hash = md5_file($realPath);

        return sprintf('%s?v=%s', $path, $hash);
    }

    private function join(string $base, string $path): string
    {
        return $path ? rtrim($base, '/').'/'.ltrim($path, '/') : $base;
    }
}
