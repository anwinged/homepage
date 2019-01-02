<?php

use Homepage\HtmlPrettierBundle\HtmlPrettierBundle;
use Homepage\SiteMapBundle\SiteMapBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles(): array
    {
        return [
            SiteMapBundle::class,
            HtmlPrettierBundle::class,
        ];
    }
}
