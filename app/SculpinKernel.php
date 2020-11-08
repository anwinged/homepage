<?php

declare(strict_types=1);

use Homepage\HtmlPrettierBundle\HtmlPrettierBundle;
use Homepage\SiteMapBundle\SiteMapBundle;
use Homepage\TwigExtensionBundle\TwigExtensionBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles(): array
    {
        return [
            TwigExtensionBundle::class,
            SiteMapBundle::class,
            HtmlPrettierBundle::class,
        ];
    }
}
