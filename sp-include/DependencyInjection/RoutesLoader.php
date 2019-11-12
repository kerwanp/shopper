<?php

namespace Shopper\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
use Shopper\Annotation\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;

class RoutesLoader
{

    public function __construct()
    {
    }

    public function getLoader()
    {
        $loader = new AnnotationDirectoryLoader(
            new FileLocator(__DIR__ . '/../Controller/'),
            new AnnotatedRouteControllerLoader(
                new AnnotationReader()
            )
        );

        return $loader->load(__DIR__ . '/../Controller/');
    }

}