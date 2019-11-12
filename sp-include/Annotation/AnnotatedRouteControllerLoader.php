<?php

namespace Shopper\Annotation;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;

/**
 * AnnotatedRouteControllerLoader is an implementation of AnnotationClassLoader
 * that sets the '_controller' default based on the class and method names.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class AnnotatedRouteControllerLoader extends AnnotationClassLoader
{
    /**
     * Configures the _controller default parameter of a given Route instance.
     *
     * @param Route $route
     * @param ReflectionClass $class
     * @param ReflectionMethod $method
     * @param mixed $annot The annotation class instance
     */
    protected function configureRoute(Route $route, ReflectionClass $class, ReflectionMethod $method, $annot)
    {
        if ('__invoke' === $method->getName()) {
            $route->setDefault('_controller', $class->getName());
        } else {
            $route->setDefault('_controller', $class->getName().'::'.$method->getName());
        }
    }

    /**
     * Makes the default route name more sane by removing common keywords.
     *
     * @param ReflectionClass $class
     * @param ReflectionMethod $method
     * @return string
     */
    protected function getDefaultRouteName(ReflectionClass $class, ReflectionMethod $method)
    {
        return preg_replace([
            '/(bundle|controller)_/',
            '/action(_\d+)?$/',
            '/__/',
        ], [
            '_',
            '\\1',
            '_',
        ], parent::getDefaultRouteName($class, $method));
    }
}