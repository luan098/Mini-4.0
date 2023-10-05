<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends FrontController
{
    const ROUTE = 'home';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        parent::__construct();
    }

    #[Route(methods: ['GET'])]
    public function index()
    {
        (new UsersController)->index();
    }
}
