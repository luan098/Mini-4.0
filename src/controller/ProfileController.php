<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\Uploads;
use Mini\model\Users;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirectReturn;
use function Mini\utils\toastResult;

#[Route(defaults: ['NO-PERMISSIONS-REQUIRED'])]
class ProfileController extends FrontController
{
    const ROUTE = 'profile';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        $this->model = new Users;
        parent::__construct();
    }

    #[Route(methods: ['GET'])]
    public function index()
    {
        $this->edit();
    }

    #[Route(methods: ['GET'])]
    public function edit()
    {
        $user = $this->model->findById($_SESSION['user']->id);
        $uploadCover = (new Uploads)->findById($user->id_upload_cover);

        require APP . "view/{$this->route}/profile.php";
    }

    #[Route(name: 'Salvar Edição', methods: ['POST'])]
    public function handleEdit()
    {
        $user = $this->model->findById($_SESSION['user']->id);

        $result = $this->model->update([
            'name'  => $_POST['name'],
            'email' => $_POST['email'],
        ], 'id', $user->id);

        if (isset($_POST['new-password']) && $_POST['new-password'] && $_POST['new-password'] === $_POST['confirm-new-password']) {
            $hash = md5($_POST['password']);
            $this->model->update(['password' => $hash], 'id', $user->id);
        };

        if (isset($_FILES['file']) && $_FILES['file'] && isset($_FILES['file']['name']) && $_FILES['file']['name']) {
            if ($user->id_upload_cover) $this->model->deleteFile($user->id, $user->id_upload_cover);

            $resultInsert = $this->model->insertUpload($_FILES['file'], $user->id);

            $this->model->update([
                'id_upload_cover' => $resultInsert->lastId,
            ], 'id', $user->id);
        }

        $this->model->updateSessionUserData($user->id);

        toastResult($result);
        redirectReturn($this->route);
    }
}
