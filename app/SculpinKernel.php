<?php

use Homepage\HtmlPrettierBundle\HtmlPrettierBundle;
//use Nickpeirson\Sculpin\Bundle\SitemapBundle\SculpinSitemapBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles(): array
    {
        return [
//            SculpinSitemapBundle::class,
            HtmlPrettierBundle::class,
        ];
    }
}
