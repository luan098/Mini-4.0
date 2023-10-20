<?php

namespace Mini\controller;

use Mini\core\FrontController;
use Mini\model\Users;
use Mini\model\UserTypes;
use Mini\utils\EmailConnection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

use function Mini\utils\redirect;
use function Mini\utils\returnJson;
use function Mini\utils\toast;

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
        $user = (new Users)->findOneBy(['email' => $_POST['email'], 'password' => md5($_POST['password'])]);

        if (!$user) returnJson(['error' => true, 'message' => 'Your email or password may be incorrect.']);
        if (!$user->approved) returnJson(['error' => true, 'message' => 'You have not been approved yet.']);

        (new Users)->updateSessionUserData($user->id);

        if ($_POST['remember'] ?? false) {
            $expire = (time() + (30 * 24 * 3600));
            $lockScreenLoginToken = uniqid($user->id);

            setcookie('auth', $lockScreenLoginToken, $expire, '/');

            (new Users)->update(['lock_screen_login_token' => $lockScreenLoginToken], 'id', $user->id);
        }

        returnJson(['error' => false, 'message' => 'Login successful, welcome']);
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
        (new Users)->update(['temp_password' => $alternativePassword], 'id', $user->id);

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
        $user = (new Users)->findOneBy(['temp_password' => $_POST['temp_password']]);
        if (!$user) returnJson(['error' => true, 'message' => 'Invalid recover code.']);
        if ($_POST['password'] != $_POST['confirm_password']) returnJson(['error' => true, 'message' => "Password didn't match."]);

        (new Users)->update(['password' => md5($_POST['password']), 'temp_password' => ''], 'id', $user->id);

        (new Users)->updateSessionUserData($user->id);

        returnJson(['error' => false, 'message' => 'Password changed Successful.']);
    }

    #[Route(methods: ['GET'])]
    public function register()
    {
        $userTypes = (new UserTypes)->findBy(['status'=> 1])->data;
        require APP . "view/{$this->route}/register.php";
    }

    #[Route(methods: ['POST'])]
    public function handleRegister()
    {
        if ($_POST['password'] != $_POST['confirm_password']) returnJson(['error' => true, 'message' => 'Passwords didn\'t match.']);
        if ((new Users)->findOneBy(['email' => $_POST['email']])) returnJson(['error' => true, 'message' => 'This email is already in use, try recover your account.']);
        if ((new Users)->findOneBy(['cpf_cnpj' => $_POST['cpf_cnpj']])) returnJson(['error' => true, 'message' => 'This cpf/cnpj is already in use.']);

        $result = (new Users)->insert([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password']),
            'id_user_type' => $_POST['id_user_type'],
            'terms' => $_POST['terms'],
        ]);
        
        if ($result->error) returnJson(['error' => true, 'message' => $result->message]);

        toast('success', 'Account created successful, await till the the team aprove your account.');
        returnJson(['error' => false, 'message' => '']);
    }

    #[Route(methods: ['GET'])]
    public function lockScreen()
    {
        $tokenData = $_COOKIE['auth'] ?? '';
        $user = (new Users)->findOneBy(['lock_screen_login_token' => $tokenData ?? false]);
        if (!$user) redirect("$this->route");

        $user = (new Users)->findByIdWithImage($user->id);

        require APP . "view/{$this->route}/lock_screen.php";
    }

    #[Route(methods: ['POST'])]
    public function handleLoginLockScreen()
    {
        if (!isset($_COOKIE['auth']) || !$_COOKIE['auth']) returnJson(['error' => true, 'message' => 'You don\'t have access to this account, try access by the login screen.']);

        $tokenData = json_decode($_COOKIE['auth']);

        $user = (new Users)->findOneBy(['lock_screen_login_token' => $tokenData, 'password' => md5($_POST['password'])]);
        if (!$user) returnJson(['error' => true, 'message' => 'Invalid token or password, try to again or back to login screen.']);

        $expire = (time() + (30 * 24 * 3600));
        $lockScreenLoginToken = uniqid($user->id);

        setcookie('auth', $lockScreenLoginToken, $expire, '/');

        (new Users)->update(['lock_screen_login_token' => $lockScreenLoginToken], 'id', $user->id);

        $_POST['email'] = $user->email;
        $this->handleLogin();
    }
}
