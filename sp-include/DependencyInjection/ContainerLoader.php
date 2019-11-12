<?php

namespace Shopper\DependencyInjection;

use Shopper\Kernel;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerLoader
{

    private $containerBuilder;
    private $routes;

    public function __construct(Routing\RouteCollection $routes)
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->routes = $routes;

        $this->setLoader();
        $this->register();
    }

    private function setLoader()
    {
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yaml');
    }

    private function register()
    {
        $this->containerBuilder->register('context', Routing\RequestContext::class);
        $this->containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$this->routes, new Reference('context')])
        ;
        $this->containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
        $this->containerBuilder->register('controller_resolver', HttpKernel\Controller\ContainerControllerResolver::class);
        $this->containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $this->containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')])
        ;
        $this->containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8'])
        ;
        $this->containerBuilder->register('dispatcher', EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')]);
        $this->containerBuilder->register('framework', Kernel::class)
            ->setArguments([
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ])
        ;
    }

    public function getContainerBuilder()
    {
        return $this->containerBuilder;
    }



}