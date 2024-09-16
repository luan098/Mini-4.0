<?php

namespace Mini\controller;

use Exception;
use Mini\core\FrontController;
use Mini\model\Users;
use Mini\model\UserTypes;
use Mini\utils\EmailConnection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

use function Mini\utils\redirect;
use function Mini\utils\returnJson;
use function Mini\utils\toast;
use function Mini\utils\toastError;
use function Mini\utils\toastResult;

#[Route(defaults: ['NO-AUTHENTICATE'])]
class EntryController extends FrontController
{
    const ROUTE = 'entry';
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
        $this->login();
    }

    #[Route(methods: ['GET'])]
    public function login()
    {

        $tokenData = $_COOKIE['auth'] ?? '';
        if ($tokenData) {
            $user = (new Users)->findOneBy(['lock_screen_login_token' => $tokenData]);
            if ($user) redirect("$this->route/lock-screen");
        }

        require APP . "view/{$this->route}/login.php";
    }

    #[Route(methods: ['POST'])]
    public function handleLogin()
    {
        try {
            $user = (new Users)->findOneBy(['email' => $_POST['email']]);

            if (!Users::comparePasswords($_POST['password'], $user->password ?? '')) $user = false;

            if (!$user) throw new Exception('E-mail ou senha incorretos.');
            if (!$user->approved) throw new Exception('Seu usuário não foi aprovado ainda.');

            (new Users)->updateSessionUserData($user->id);

            if ($_POST['remember'] ?? false) {
                $expire = (time() + (30 * 24 * 3600));
                $lockScreenLoginToken = uniqid($user->id);

                setcookie('auth', $lockScreenLoginToken, $expire, '/');

                (new Users)->update(['lock_screen_login_token' => $lockScreenLoginToken], 'id', $user->id);
            }

            redirect(HomeController::ROUTE);
        } catch (\Throwable $th) {
            toastError($th);
            redirect("$this->route/login?email={$_POST['email']}");
        }
    }

    #[Route(methods: ['GET'])]
    public function handleLogout()
    {
        (new Users)->update(['lock_screen_login_token' => ''], 'id', $_SESSION['user']->id);

        setcookie("auth", "", time() - 3600, "/");
        session_destroy();

        redirect("$this->route");
    }

    #[Route(methods: ['GET'])]
    public function recoverAccount()
    {
        require APP . "view/{$this->route}/recover_account.php";
    }

    #[Route(methods: ['POST'])]
    public function handleRecoverAccount()
    {
        $user = (new Users)->findOneBy(['email' => $_POST['email']]);
        if (!$user) returnJson(['error' => true, 'message' => 'Your email or password may be incorrect.']);

        $alternativePassword = md5(uniqid("Rec"));

        $currentDateTime = date("d/m/Y H:i:s");
        $resetLink = URL . EntryController::ROUTE . "/change-password";

        $message = "
            <strong>Date/Time:</strong> {$currentDateTime} <br /><br />
            Hello <strong>{$user->name}</strong>,<br /><br />
            You have requested to reset your password for your " . APP_NAME . " account.<br />
            To reset your password, click the link below:<br />
            <a href='{$resetLink}?code={$alternativePassword}' style='color: #4973b2; text-decoration: none'>Reset My Password</a><br /><br />
            If the link above doesn't work, you can use the <strong>following code</strong> to reset your password:<br /><br />
            <strong style='font-size: 24px; text-align: center; display: block; color: #4973b2;'>{$alternativePassword}</strong><br />
            Just go to the <a href='{$resetLink}' style='color: #4973b2; text-decoration: none'>password reset </a> page and use this code to complete the process.<br /><br />
            If you did not request this password reset, you can ignore this email. If you receive multiple unauthorized requests, please contact us.
        ";

        $emailConnection = new EmailConnection();
        $email =  $emailConnection->createEmail();

        $email->to($user->email)
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Recover Account')
            ->html(EmailConnection::useTemplate($message, $user->email, 'Password Recovery - ' . APP_NAME));

        $emailConnection->mailer->send($email);

        (new Users)->update(['temp_password' => $alternativePassword], 'id', $user->id);

        returnJson(['error' => false, 'message' => 'Recover email sended successful, check your e-mail to recover your account']);
    }

    #[Route(methods: ['GET'])]
    public function changePassword()
    {
        require APP . "view/{$this->route}/change_password.php";
    }

    #[Route(methods: ['POST'])]
    public function handleChangePassword()
    {
        try {
            $user = (new Users)->findOneBy(['temp_password' => $_POST['temp_password']]);

            if (!$user) throw new Exception('Código de recuperação inválido.');
            if ($_POST['password'] != $_POST['confirm_password']) throw new Exception('A senha e a confirmação não combinam.');

            $result = (new Users)->update(['password' => Users::encryptPassword($_POST['password']), 'temp_password' => ''], 'id', $user->id);

            (new Users)->updateSessionUserData($user->id);

            toastResult($result);
            redirect($this->route);
        } catch (\Throwable $th) {
            toastError($th);
            redirect("$this->route/changePassword?code={$_POST['temp_password']}");
        }
    }

    #[Route(methods: ['GET'])]
    public function register()
    {
        $userTypes = (new UserTypes)->findBy(['status' => 1])->data;
        require APP . "view/{$this->route}/register.php";
    }

    #[Route(methods: ['POST'])]
    public function handleRegister()
    {
        try {
            if ($_POST['password'] != $_POST['confirm_password']) throw new Exception('A senha e a confirmação da senha não são iguais.');
            if ((new Users)->findOneBy(['email' => $_POST['email']])) throw new Exception('Este e-mail já esta em uso, tente recuperar sua conta.');

            $result = (new Users)->insert([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => Users::encryptPassword($_POST['password']),
                'id_user_type' => $_POST['id_user_type'],
                'terms' => $_POST['terms'],
            ]);

            if ($result->error) returnJson(['error' => true, 'message' => $result->message]);

            toast('success', 'Conta criada com sucesso aguarde a aprovação para acessar o sistema.');
            redirect($this->route);
        } catch (\Throwable $th) {
            toastError($th);
            redirect("$this->route/register?name={$_POST['name']}&email={$_POST['email']}&id_user_type={$_POST['id_user_type']}");
        }
    }

    #[Route(methods: ['GET'])]
    public function lockScreen()
    {
        $tokenData = $_COOKIE['auth'] ?? '';
        $user = (new Users)->findOneBy(['lock_screen_login_token' => $tokenData ?? false]);

        if (!$user) $this->handleLogout();

        $user = (new Users)->findByIdWithImage($user->id);

        require APP . "view/{$this->route}/lock_screen.php";
    }

    #[Route(methods: ['POST'])]
    public function handleLoginLockScreen()
    {
        try {
            if (!isset($_COOKIE['auth']) || !$_COOKIE['auth']) throw new Exception('Você não tem acesso a essa conta, tente acessar pela tela de login.');
            
            $tokenData = json_decode($_COOKIE['auth']);
            
            $user = (new Users)->findOneBy(['lock_screen_login_token' => $tokenData]);
            if (!Users::comparePasswords($_POST['password'], $user->password ?? '')) $user = false;

            if (!$user) throw new Exception('Token ou senha inválidos, tente novamente ou acesse a tela de login.');

            $expire = (time() + (30 * 24 * 3600));
            $lockScreenLoginToken = uniqid($user->id);

            setcookie('auth', $lockScreenLoginToken, $expire, '/');

            (new Users)->update(['lock_screen_login_token' => $lockScreenLoginToken], 'id', $user->id);

            $_POST['email'] = $user->email;
            $this->handleLogin();
        } catch (\Throwable $th) {
            toastError($th);
            redirect("$this->route/lockScreen");
        }
    }
}
