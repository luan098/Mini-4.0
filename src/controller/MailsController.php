<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\Users;
use Symfony\Component\Routing\Annotation\Route;

use function Mini\utils\returnJson;

#[Route(defaults: ['NO-AUTHENTICATE'])]
class MailsController extends FrontController
{
    const ROUTE = 'mails';
    public $route;

    public function __construct()
    {
        $this->route = self::ROUTE;
        $this->model = new Mails();
        parent::__construct();
    }

    #[Route(methods: ['GET'])]
    public function unsubscribe()
    {
        require APP . "view/{$this->route}/unsubscribe.php";
    }

    #[Route(methods: ['POST'])]
    public function handleUnsubscribe()
    {
        $result = (new Users)->update(['receive_emails' => 0], 'email', $_POST['em']);
       
        returnJson(['error' => false, 'message' => 'Unsubscribed Successful.']);
    }

    #[Route(methods: ['GET'])]
    public function sendFiliationRequestsToAdmin($key = null)
    {
        if ($key != "np1YAJyJE7") returnJson(['error' => false, 'message' => 'You don\'t have permission to make this request.']);

        try {
            $this->model->sendFiliationRequestsToAdmin();
            returnJson(['error' => false, 'message' => 'E-mail sended']);
        } catch (\Throwable $th) {
            returnJson(['error' => true, 'message' => $th->getMessage()]);
        }
    }
}
