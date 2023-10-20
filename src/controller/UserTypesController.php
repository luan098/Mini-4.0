<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\UserTypePermissions;
use Mini\model\UserTypes;
use Mini\utils\ControllerScanner;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\redirect;
use function Mini\utils\redirectReturn;
use function Mini\utils\returnJson;
use function Mini\utils\toastResult;

#[Route(name: 'Tipos de Usuário')]
class UserTypesController extends FrontController
{
    const ROUTE = 'user-types';
    public $route;
    public $model;

    public function __construct()
    {
        $this->route = self::ROUTE;
        $this->model = new UserTypes;
        parent::__construct();
    }

    #[Route(name: 'Listar', methods: ['GET'])]
    public function index()
    {
        require APP . "view/{$this->route}/index.php";
    }

    #[Route(name: 'Tela de Adição', methods: ['GET'])]
    public function add()
    {
        require APP . "view/{$this->route}/user-type.php";
    }

    #[Route(name: 'Salvar Adição', methods: ['POST'])]
    public function handleAdd()
    {
        $result = $this->model->insert([
            'name' => $_POST['name'],
            'is_admin' => $_POST['is_admin'] ?? 0,
            'is_customer' => $_POST['is_customer'] ?? 0,
        ]);
        
        toastResult($result);

        if ($result->error) redirectReturn($this->route);

        redirect("$this->route/edit/$result->lastId");
    }

    #[Route(name: 'Tela de Edição', methods: ['GET'])]
    public function edit(int $idUserType)
    {
        $userType = $this->model->findById($idUserType);

        require APP . "view/{$this->route}/user-type.php";
    }

    #[Route(name: 'Salvar Edição', methods: ['POST'])]
    public function handleEdit(int $idUserType)
    {
        $result = $this->model->update([
            'name' => $_POST['name'],
            'is_admin' => $_POST['is_admin'] ?? 0,
            'is_customer' => $_POST['is_customer'] ?? 0,
            'status' => $_POST['status'] ?? 0,
        ], 'id', $idUserType);

        toastResult($result);
        redirect("$this->route/edit/$idUserType");
    }

    #[Route(name: 'Tela de Controle de Acessos', methods: ['GET'])]
    public function access(int $idUserType)
    {
        $userType = $this->model->findById($idUserType);

        $permissions = (new UserTypePermissions)->findFullRouteByIdUserType($idUserType);

        $controllerScanner = new ControllerScanner(APP . 'controller');
        $routes = $controllerScanner->scanControllers();

        require APP . "view/{$this->route}/user-type.php";
    }

    #[Route(name: 'Salvar Controle de Acessos', methods: ['POST'])]
    public function handleEditAccess(int $idUserType)
    {
        $userType = $this->model->findById($idUserType);

        $route = explode('/', $_POST['route']);

        $filters = ['id_user_type' => $idUserType, 'route' => $route[0]];
        if (isset($route[1]) && $route[1]) $filters['sub_route'] = $route[1];

        $permission = (new UserTypePermissions)->findOneBy($filters);

        if ($permission) {
            $result = (new UserTypePermissions)->delete($permission->id);
        } else {
            $result = (new UserTypePermissions)->insert([
                'route' => $route[0],
                'sub_route' => $route[1] ?? null,
                'id_user_type' => $idUserType,
            ]);
        }

        returnJson($result);
    }
}
