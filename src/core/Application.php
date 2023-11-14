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
    private $route = '';
    private $subRoute = '';
    private $params = [];

    public function __construct()
    {
        try {
            $this->processUrlParts();
            $this->handleRequest();
        } catch (\Throwable $th) {
            $this->loadUnexpectedErrorPage($th);
        }
    }
    private function processUrlParts()
    {
        $router = new Router($_GET['url']);

        $route = $router->getRoute();
        $subRoute = $router->getSubRoute();

        $this->route = $route ? self::formatUrlPart($route) : 'Home';
        $this->subRoute = $subRoute ? self::formatUrlPart($subRoute) : 'index';
        $this->params = $router->getParams();
    }

    private function handleRequest()
    {
        if (strtolower($this->route) == 'datatable') $this->loadAjaxMasterController();

        if (!$this->isValidRoute()) $this->loadPageNotFound();

        if (!$this->isValidHttpMethod()) $this->handleInvalidMethod();

        if (!$this->verifyAuthentication()) $this->redirectToLogin();

        if (!PermissionChecker::havePermission($this->route, $this->subRoute)) $this->loadUnauthorizedPage();

        $this->invokeControllerMethod();
    }

    /**
     * @deprecated version 0.2 will be removed in the future, cause a lot of security problems
     */
    private function loadAjaxMasterController()
    {
        $controller = '\\Mini\\core\\AjaxMasterController';
        $controller = new $controller();
        call_user_func_array(array($controller, $this->subRoute), ['model', $this->subRoute, 'getDatatable']);
        exit();
    }

    private function isValidRoute()
    {
        return file_exists(APP . "controller/" . ucfirst($this->route) . 'Controller.php');
    }

    private function isValidHttpMethod()
    {
        try {
            $controllerClass = "\\Mini\\controller\\" . ucfirst($this->route) . 'Controller';
            $reflectionMethod = new \ReflectionMethod($controllerClass, $this->subRoute);

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

    private function loadPageNotFound()
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']->id) $this->redirectToLogin();

        (new ErrorsController)->notFound();
        exit();
    }

    private function verifyAuthentication()
    {
        $noAuthenticate = $this->verifyNoAuthenticate($this->route, $this->subRoute);

        if ($noAuthenticate || (isset($_SESSION['user']) && $_SESSION['user']->id)) {
            return true;
        }

        return false;
    }

    private function redirectToLogin()
    {
        redirect(EntryController::ROUTE);
    }

    private function invokeControllerMethod()
    {
        $controllerClass = "\\Mini\\controller\\" . ucfirst($this->route) . 'Controller';
        $controller = new $controllerClass();

        if (!empty($this->params)) {
            call_user_func_array(array($controller, $this->subRoute), $this->params);
        } else {
            $controller->{$this->subRoute}();
        }
    }

    public static function formatUrlPart($part)
    {
        return implode("", array_map(function ($fragment) {
            return ucfirst($fragment);
        }, explode('-', $part)));
    }

    private function loadUnauthorizedPage()
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']->id) $this->redirectToLogin();
        
        (new ErrorsController)->unauthorized();
        exit();
    }

    private function loadUnexpectedErrorPage(object $error)
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']->id) $this->redirectToLogin();

        $_POST['error'] = $error;
        (new ErrorsController)->unexpected();
        exit();
    }

    private static function verifyNoAuthenticate(string $urlController, string $urlAction)
    {
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

    private function handleInvalidMethod()
    {
        header('HTTP/1.0 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit();
    }
}
