<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Shopper\DependencyInjection\ContainerLoader;
use Shopper\DependencyInjection\RoutesLoader;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$autoloader = require __DIR__ . '/../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($autoloader, "loadClass"));

$routesLoader = new RoutesLoader();
$loader = $routesLoader->getLoader();

$containerLoader = new ContainerLoader($loader);
$container = $containerLoader->getContainerBuilder();

$request = Request::createFromGlobals();
$response = $container->get('framework')->handle($request);

$response->send();