<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['NO-PERMISSIONS-REQUIRED'])]
class ErrorsController extends FrontController
{
    const ROUTE = 'errors';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        parent::__construct();
    }

    public function index()
    {
        $this->notFound();
    }

    public function notFound()
    {
        require APP . "view/{$this->route}/404.php";
    }

    public function unauthorized()
    {
        require APP . "view/{$this->route}/401.php";
    }
}
