<?php

namespace Mini\utils;

use ReflectionClass;
use ReflectionMethod;

class MiniRoute
{
    public $route_name;
    public $sub_route_name;
    public $route;
    public $sub_route;

    public function __construct(string $route, string|null $subRoute = '', string|null $routeName = null, string|null $subRouteName = null)
    {
        $this->route = $route;
        $this->sub_route = $subRoute;
        $this->route_name = isset($routeName) && $routeName ? $routeName : $route;
        $this->sub_route_name = isset($subRouteName) && $subRouteName ? $subRouteName : $subRoute;
    }
}

final class ControllerScanner
{
    private $controllerDirectory;

    public function __construct($controllerDirectory)
    {
        $this->controllerDirectory = $controllerDirectory;
    }

    public function scanControllers(): array
    {
        // Get all PHP files in the controller directory
        $files = glob($this->controllerDirectory . '/*.php');

        foreach ($files as $file) {
            require_once $file;
            $className = 'Mini\\controller\\' . basename($file, '.php');

            if (class_exists($className)) {
                $reflectionClass = new ReflectionClass($className);

                // Check if the class has the #[Route(name)] annotation
                $classAnnotation = $this->getClassAnnotation($reflectionClass);
                $mainRouteName = $classAnnotation ?: $className::ROUTE;

                // Get public methods with the #[Route(name)] annotation
                $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

                // Skip controllers with #[Route(defaults: ['NO-AUTHENTICATE'])] annotation
                if ($this->hasNoAuthenticateAnnotation($reflectionClass)) {
                    continue;
                }

                // Skip controllers with #[Route(defaults: ['NO-AUTHENTICATE'])] annotation
                if ($this->hasNoPermissionsRequiredAnnotation($reflectionClass)) {
                    continue;
                }

                $subRoutes = [];

                foreach ($methods as $method) {
                    // Skip methods with #[Route(defaults: ['NO-AUTHENTICATE'])] annotation Or without the Route Annotation
                    if (!$this->hasRouteAnnotation($method) || $this->hasNoAuthenticateAnnotation($method) || $this->hasNoPermissionsRequiredAnnotation($method)) {
                        continue;
                    }

                    // Get the method name or the #[Route(name)] annotation
                    $methodAnnotation = $this->getMethodAnnotation($method);
                    $subRouteName = $methodAnnotation ?: $method->getName();

                    $subRoutes[] = new MiniRoute($className::ROUTE, $method->getName(), $mainRouteName, $subRouteName);
                }

                if (sizeof($subRoutes) > 0) {
                    $routes[] = new MiniRoute($className::ROUTE, '', $mainRouteName);
                    $routes = array_merge($routes, $subRoutes);
                }
            }
        }

        return $routes;
    }

    private function getClassAnnotation(ReflectionClass $class)
    {
        $attributes = $class->getAttributes();

        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'Route')) {
                return $attribute->getArguments()['name'];
            }
        }

        return null;
    }

    private function getMethodAnnotation(ReflectionMethod $method)
    {
        $attributes = $method->getAttributes();

        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'Route')) {
                return $attribute->getArguments()['name'];
            }
        }

        return null;
    }

    private function hasRouteAnnotation(ReflectionMethod $method)
    {
        $attributes = $method->getAttributes();

        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'Route')) {
                return true;
            }
        }

        return false;
    }

    private function hasNoAuthenticateAnnotation($reflection)
    {
        if ($reflection instanceof ReflectionMethod || $reflection instanceof ReflectionClass) {
            $attributes = $reflection->getAttributes();
            foreach ($attributes as $attribute) {
                if (str_contains($attribute->getName(), 'Route') && isset($attribute->getArguments()['defaults']) && in_array('NO-AUTHENTICATE', $attribute->getArguments()['defaults'])) {
                    return true;
                }
            }
        }

        return false;
    }

    private function hasNoPermissionsRequiredAnnotation($reflection)
    {
        if ($reflection instanceof ReflectionMethod || $reflection instanceof ReflectionClass) {
            $attributes = $reflection->getAttributes();
            foreach ($attributes as $attribute) {
                if (str_contains($attribute->getName(), 'Route') && isset($attribute->getArguments()['defaults']) && in_array('NO-PERMISSIONS-REQUIRED', $attribute->getArguments()['defaults'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
