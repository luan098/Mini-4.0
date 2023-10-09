<?php

namespace Mini\core;

use Mini\controller\EntryController;
use Mini\controller\ErrorsController;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirect;

error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);

require APP . 'utils/Functions.php';

final class Application
{
    private object $controller;
    private string $url_controller = '';
    private string|null $url_action = '';
    private array $url_params = [];

    public function __construct()
    {
        $this->splitUrl();

        if (strtolower($this->url_controller) == 'datatable') {
            $this->loadAjaxMasterController();
        } else if ($this->isValidController()) {
            $this->loadControllerMethod();
        } else {
            $this->loadPageNotFound();
        }
    }

    /**
     * Load the default HomeController
     */
    private function redirectToLogin()
    {
        redirect(EntryController::ROUTE);
    }

    /**
     * Load the AjaxMasterController for 'datatable'
     */
    private function loadAjaxMasterController()
    {
        $controller = '\\Mini\\core\\AjaxMasterController';
        $controller = new $controller();
        call_user_func_array(array($controller, $this->url_action), ['model', $this->url_action, 'getDatatable']);
    }

    /**
     * Check if the controller exists and load it
     */
    private function loadControllerMethod()
    {
        $controllerClass = "\\Mini\\controller\\" . ucfirst($this->url_controller) . 'Controller';
        $this->controller = new $controllerClass();

        if ($this->isValidMethod()) {
            if ($this->verifyAuthentication()) {
                if ($this->havePermission()) {
                    $this->invokeControllerMethod();
                } else {
                    $this->loadUnauthorizedFound();
                }
            } else {
                $this->redirectToLogin();
            }
        } else {
            $this->handleInvalidMethod();
        }
    }

    /**
     * Check if the controller needs permission and if the user have permission to access him
     */
    private function havePermission()
    {
        if ($this->verifyNoAuthenticate()) return true;

        if ($this->verifyNoPermissionRequired()) return true;

        if ($_SESSION['user_type']->is_admin) return true;

        $permissionKeyMainRoute = array_search($_GET['pg'] . '/', $_SESSION['permitted_routes']);
        if ($permissionKeyMainRoute || $permissionKeyMainRoute === 0) return true;

        $permissionKey = array_search($_GET['pg'] . '/' . $this->url_action, $_SESSION['permitted_routes']);
        return $permissionKey || $permissionKey === 0;
    }

    /**
     * Invoke the controller method with or without parameters
     */
    private function invokeControllerMethod()
    {
        if (!empty($this->url_params)) {
            call_user_func_array(array($this->controller, $this->url_action), $this->url_params);
        } else {
            $this->controller->{$this->url_action}();
        }
    }

    /**
     * Check if the controller file exists
     */
    private function isValidController()
    {
        return file_exists(APP . "controller/" . ucfirst($this->url_controller) . 'Controller.php');
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Remove certain keywords from URL
            $this->removeKeywords($url);
        }

        $this->processUrlParts($url);
    }

    /**
     * Remove certain keywords from the URL
     */
    private function removeKeywords(&$url)
    {
        $keywords = ['ajax', 'api', 'app'];
        if (in_array(strtolower($url[0]), $keywords)) {
            array_shift($url);
        }
    }

    /**
     * Process URL parts and set controller, action, and parameters
     */
    private function processUrlParts($url)
    {
        $this->url_controller = isset($url[0]) ? $this->formatUrlPart($url[0]) : 'Home';
        $this->url_action = isset($url[1]) ? $this->formatUrlPart($url[1]) : 'index';

        unset($url[0], $url[1]);
        $this->url_params = array_values($url ?? []);
    }

    /**
     * Format a URL part to be used as a controller or action name
     */
    private function formatUrlPart($part)
    {
        return implode("", array_map(function ($fragment) {
            return ucfirst($fragment);
        }, explode('-', $part)));
    }

    /**
     * Load the ErrorsController
     */
    private function loadPageNotFound()
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']->id) $this->redirectToLogin();
        (new ErrorsController)->notFound();
    }

    /**
     * Load the ErrorsController
     */
    private function loadUnauthorizedFound()
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']->id) $this->redirectToLogin();
        (new ErrorsController)->unauthorized();
    }

    private function isValidMethod()
    {
        try {
            $controllerClass = "\\Mini\\controller\\" . ucfirst($this->url_controller) . 'Controller';
            $reflectionMethod = new \ReflectionMethod($controllerClass, $this->url_action);

            // Check for Symfony-like annotations
            $annotations = $reflectionMethod->getAttributes(Route::class);
            foreach ($annotations as $annotation) {
                $route = $annotation->newInstance();
                if (in_array($_SERVER['REQUEST_METHOD'], $route->getMethods())) {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            $this->loadPageNotFound();
            exit;
        }

        return false;
    }

    private function verifyAuthentication()
    {
        $noAuthenticate = $this->verifyNoAuthenticate();

        if ($noAuthenticate || (isset($_SESSION['user']) && $_SESSION['user']->id)) return true;

        return false;
    }

    private function verifyNoAuthenticate()
    {
        $controllerClass = "\\Mini\\controller\\" . ucfirst($this->url_controller) . 'Controller';
        $reflectionClass = new \ReflectionClass($controllerClass);
        $reflectionMethod = new \ReflectionMethod($controllerClass, $this->url_action);

        $classAnnotations = $reflectionClass->getAttributes(Route::class);
        foreach ($classAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-AUTHENTICATE', $route->getDefaults())) {
                return true;
            }
        }

        $routeAnnotations = $reflectionMethod->getAttributes(Route::class);
        foreach ($routeAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-AUTHENTICATE', $route->getDefaults())) {
                return true;
            }
        }

        return false;
    }

    private function verifyNoPermissionRequired()
    {
        $controllerClass = "\\Mini\\controller\\" . ucfirst($this->url_controller) . 'Controller';
        $reflectionClass = new \ReflectionClass($controllerClass);
        $reflectionMethod = new \ReflectionMethod($controllerClass, $this->url_action);

        $classAnnotations = $reflectionClass->getAttributes(Route::class);
        foreach ($classAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-PERMISSIONS-REQUIRED', $route->getDefaults())) {
                return true;
            }
        }

        $routeAnnotations = $reflectionMethod->getAttributes(Route::class);
        foreach ($routeAnnotations as $annotation) {
            $route = $annotation->newInstance();
            if (in_array('NO-PERMISSIONS-REQUIRED', $route->getDefaults())) {
                return true;
            }
        }

        return false;
    }

    private function handleInvalidMethod()
    {
        header('HTTP/1.0 405 Method Not Allowed');
        echo 'Method Not Allowed';
    }
}
