<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\SettingsEmail;
use Mini\model\SettingsGeneral;
use Mini\utils\ImageManipulator;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirect;
use function Mini\utils\toastResult;

#[Route(name: 'Configurações')]
class SettingsController extends FrontController
{
    const ROUTE = 'settings';
    public $route;

    public function __construct()
    {
        $this->route = self::ROUTE;
        parent::__construct();
    }

    #[Route(name: 'Configurações Gerais', methods: ['GET'])]
    public function index()
    {
        $settingsGeneral = (new SettingsGeneral)->findById(1);

        require APP . "view/{$this->route}/index.php";
    }

    #[Route(name: 'Salvar Edição de Configurações Gerais', methods: ['POST'])]
    public function handleEditGeneral()
    {
        $path = ROOT . 'public/images/config';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if (isset($_FILES['logo']) && $_FILES['logo'] && $_FILES['logo']['name']) {
            (new ImageManipulator($_FILES['logo']['tmp_name'], "$path/logo.png", 165, 165, 100))->save();
        }

        if (isset($_FILES['favicon']) && $_FILES['favicon'] && $_FILES['favicon']['name']) {
            (new ImageManipulator($_FILES['favicon']['tmp_name'], "$path/favicon.png", 20, 20, 100))->save();
        }

        redirect($this->route);
    }

    #[Route(name: 'Configurações de Email', methods: ['GET'])]
    public function email()
    {
        $settingsEmail = (new SettingsEmail)->findById(1);

        require APP . "view/{$this->route}/index.php";
    }

    #[Route(name: 'Salvar Edição Configurações de Email', methods: ['POST'])]
    public function handleEditEmail()
    {
        $result = (new SettingsEmail)->update([
            'name' => $_POST['name'],
            'user_email' => $_POST['user_email'],
            'host' => $_POST['host'],
            'port' => $_POST['port'],
            'recipient' => $_POST['recipient'],
        ], 'id', 1);

        if (isset($_POST['password_email']) && $_POST['password_email']) {
            (new SettingsEmail)->update(['password_email' => $_POST['password_email']], 'id', 1);
        }

        toastResult($result);
        redirect($this->route);
    }
}
