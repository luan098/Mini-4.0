<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\Errors;
use Mini\model\Mails;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirect;

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

    public function unexpected()
    {
        if (!isset($_POST['error']) || !$_POST['error']) redirect(HomeController::ROUTE);
        $error = $_POST['error'];

        (new Errors)->insert([
            "error" => $error->getMessage() . " <br> " . $error->getTraceAsString(),
            "created_by" => $_SESSION['user']->id ?? null,
            "created_by_name" => $_SESSION['user']->name ?? null,
        ]);

        (new Mails)->sendErrorToDev($error);
        require APP . "view/{$this->route}/500.php";
    }
}
