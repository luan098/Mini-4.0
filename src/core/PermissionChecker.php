<?php

namespace Mini\core;

use Symfony\Component\Routing\Annotation\Route;

class PermissionChecker
{
    public static function havePermission(string $urlController, string $urlAction)
    {
        $urlController = ucfirst($urlController);
        $urlAction = lcfirst($urlAction);

        if (self::verifyNoAuthenticate($urlController, $urlAction)) {
            return true;
        }

        if (self::verifyNoPermissionRequired($urlController, $urlAction)) {
            return true;
        }

        if ($_SESSION['user_type']->is_admin) {
            return true;
        }

        $permissionKeyMainRoute = array_search(strtolower($urlController) . '/', $_SESSION['permitted_routes']);
        if ($permissionKeyMainRoute || $permissionKeyMainRoute === 0) {
            return true;
        }

        $permissionKey = array_search(strtolower($urlController) . '/' . $urlAction, $_SESSION['permitted_routes']);
        return $permissionKey || $permissionKey === 0;
    }

    private static function verifyNoAuthenticate(string $urlController, string $urlAction)
    {
        // Check if NO-AUTHENTICATE is set for the controller or action.
        $controllerClass = "\\Mini\\controller\\" . ucfirst($urlController) . 'Controller';
        $reflectionClass = new \ReflectionClass($controllerClass);

        $classAnnotations = $reflectionClass->getAttributes(Route::class);
        foreach ($classAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-AUTHENTICATE', $route->getDefaults())) {
                return true;
            }
        }

        $reflectionMethod = new \ReflectionMethod($controllerClass, $urlAction);

        $routeAnnotations = $reflectionMethod->getAttributes(Route::class);
        foreach ($routeAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-AUTHENTICATE', $route->getDefaults())) {
                return true;
            }
        }

        return false;
    }

    private static function verifyNoPermissionRequired(string $urlController, string $urlAction)
    {
        // Check if NO-PERMISSIONS-REQUIRED is set for the controller or action.
        $controllerClass = "\\Mini\\controller\\" . ucfirst($urlController) . 'Controller';
        $reflectionClass = new \ReflectionClass($controllerClass);

        $classAnnotations = $reflectionClass->getAttributes(Route::class);
        foreach ($classAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-PERMISSIONS-REQUIRED', $route->getDefaults())) {
                return true;
            }
        }

        $reflectionMethod = new \ReflectionMethod($controllerClass, $urlAction);

        $routeAnnotations = $reflectionMethod->getAttributes(Route::class);
        foreach ($routeAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-PERMISSIONS-REQUIRED', $route->getDefaults())) {
                return true;
            }
        }

        return false;
    }
}
