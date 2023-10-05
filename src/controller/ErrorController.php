<?php

namespace Mini\controller;

use Mini\core\FrontController;

class ErrorController extends FrontController
{
    const ROUTE = 'error';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        parent::__construct();
    }

    public function index()
    {
        require APP . "view/{$this->route}/index.php";
    }
}
