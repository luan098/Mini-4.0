<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\Uploads;
use Mini\model\Users;
use Mini\model\UserTypes;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirect;
use function Mini\utils\toastResult;

#[Route(name: 'Usuários')]
class UsersController extends FrontController
{
    const ROUTE = 'users';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        $this->model = new Users;
        parent::__construct();
    }

    #[Route(name: 'Listar', methods: ['GET'])]
    public function index()
    {
        $userTypes = (new UserTypes)->findBy(['status' => 1])->data;

        require APP . "view/{$this->route}/index.php";
    }

    #[Route(name: 'Tela de Adição', methods: ['GET'])]
    public function add()
    {
        $userTypes = (new UserTypes)->findBy()->data;

        require APP . "view/{$this->route}/user.php";
    }

    #[Route(name: 'Salvar Adição', methods: ['POST'])]
    public function handleAdd()
    {
        $result = $this->model->insert([
            'id_user_type' => $_POST['id_user_type'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'terms' => $_POST['terms'],
            'approved' => $_POST['approved'],
            'password' => Users::encryptPassword($_POST['password']),
        ]);

        toastResult($result);

        if ($result->error) redirect("$this->route/add");
        redirect("$this->route/edit/$result->lastId");
    }

    #[Route(name: 'Tela de Edição', methods: ['GET'])]
    public function edit(int $idUser)
    {
        $user = $this->model->findById($idUser);
        $userTypes = (new UserTypes)->findBy()->data;

        require APP . "view/{$this->route}/user.php";
    }

    #[Route(name: 'Salvar Edição', methods: ['POST'])]
    public function handleEdit(int $idUser)
    {
        $result = $this->model->update([
            'id_user_type' => $_POST['id_user_type'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'approved' => $_POST['approved'] ?? 0,
            'terms' => $_POST['terms'] ?? 0,
            'status' => $_POST['status'] ?? 0,
        ], 'id', $idUser);

        if (isset($_POST['password']) && $_POST['password']) {
            $hash = Users::encryptPassword($_POST['password']);
            $this->model->update(['password' => $hash], 'id', $idUser);
        };

        toastResult($result);
        redirect("$this->route/edit/$idUser");
    }

    #[Route(name: 'Imagem de Perfil', methods: ['GET'])]
    public function cover(int $idUser)
    {
        $user = $this->model->findById($idUser);
        $coverUploaded = (new Uploads)->findById($user->id_upload_cover);

        require APP . "view/{$this->route}/user.php";
    }

    #[Route(name: 'Salvar/Editar Imagem de Perfil', methods: ['POST'])]
    public function handleAddUpdateCover(int $idUser)
    {
        $user = $this->model->findById($idUser);
        if ($user->id_upload_cover) $this->model->deleteFile($user->id, $user->id_upload_cover);

        $resultInsert = $this->model->insertUpload($_FILES['file'], $user->id);

        $this->model->update([
            'id_upload_cover' => $resultInsert->lastId,
        ], 'id', $idUser);

        toastResult($resultInsert);
        redirect("$this->route/cover/$idUser");
    }
}
