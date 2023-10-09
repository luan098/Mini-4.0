<?php

namespace Mini\model;

use Mini\controller\UsersController;
use Mini\utils\EmailConnection;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class Mails
{
    function __construct()
    {
    }

    public static function sendFiliationRequestsToAdmin(): bool
    {
        $Users = new Users;
        $usersAdmin = $Users->findAdminsToMail();
        if (count($usersAdmin) <= 0) return false;

        $emailConnection = new EmailConnection();
        $usersLink = URL . UsersController::ROUTE . "?approved=0";
        $currentDateTime = date("d/m/Y H:i:s");

        foreach ($usersAdmin as $user) {
            $email =  $emailConnection->createEmail();
            $message = "
                    <strong>Date/Time:</strong> {$currentDateTime} <br /><br />
                    Hello Admins,<br /><br />
                    There are new users awaiting approval to use the Fast Import system. Please review and approve their requests as soon as possible to grant them access.<br /><br />
                    You can view and approve new user requests in the admin panel.<br />
                    <a href='{$usersLink}' style='background-color: #4973b2; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-top: 20px;'>Click To Verify</a><br /><br />
                    Thank you for your prompt attention to this matter.<br /><br />
                ";

            $email->priority(Email::PRIORITY_HIGHEST)
                ->to(new Address($user->email, $user->name))
                ->subject('Users Approval Pending')
                ->html(EmailConnection::useTemplate($message, $user->email, 'Users Approval Pending - Fast Import'));

            $emailConnection->mailer->send($email);
        }

        return true;
    }
}
