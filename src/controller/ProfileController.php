<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends FrontController
{
    const ROUTE = 'profile';
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
        $this->orders();
    }

    #[Route(methods: ['GET'])]
    public function orders()
    {
        $orders = [];

        require APP . "view/{$this->route}/profile.php";
    }
}
