<?php

namespace Mini\controller;

use Error;
use Exception;
use Mini\core\FrontController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['NO-PERMISSIONS-REQUIRED'])]
class AdminController extends FrontController
{
    const ROUTE = 'admin';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;

        if (!$_SESSION['user_type']->is_admin) {
            echo 'No Permission';
            exit();
        }

        parent::__construct();
    }

    #[Route(methods: ['GET'])]
    public function testErrorHandling()
    {
        throw new Error("Error Handling Test", 1);
    }
}
