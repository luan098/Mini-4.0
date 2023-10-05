<?php

namespace Mini\utils;

use Mini\controller\MailsController;
use Mini\model\SettingsEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class EmailConnection
{
    public object|null $settings = null;
    public TransportInterface|null $transport = null;
    public MailerInterface|null $mailer = null;

    function __construct()
    {
        $this->settings = (new SettingsEmail)->findById(1);
        $this->transport = Transport::fromDsn("smtp://{$this->settings->sender_email}:{$this->settings->sender_password}@{$this->settings->sender_host}:{$this->settings->port}");
        $this->mailer = new Mailer($this->transport);
    }

    public function createEmail(): Email
    {
        return (new Email())->from(new Address($this->settings->sender_email, $this->settings->sender_name));
    }

    public static function useTemplate(string $content, string $receiverMail, string $title = APP_NAME): string
    {
        $urlForImages = ENVIRONMENT === 'production' || ENVIRONMENT === 'development' ? URL : 'https://fastimport.net';

        return "
            <!DOCTYPE html>
            <html lang='en'>
                <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <meta http-equiv='Content-Type' content='text/html charset=UTF-8' />
                    <title>{$title}</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f7cf33; padding: 20px'>
                    <div style='background-color: #fff; border-radius: 5px; padding: 20px; max-width: 600px; margin: 0 auto'>
                        <table style='width: 100%'>
                            <tr>
                                <td style='text-align: left'>
                                    <a href='" . URL . "' style='text-decoration: none'>
                                        <img src='{$urlForImages}/images/config/logo.png' alt='" . APP_NAME . " Logo' style='max-width: 200px' />
                                    </a>
                                </td>
                                <td style='text-align: left'>
                                    <h1 style='color: #f7cf33; font-size: 42px'>" . APP_NAME . "</h1>
                                </td>
                            </tr>
                        </table>

                        {$content}

                        <br /> <br />
                        Best regards,
                        <br />
                        " . APP_NAME . " Team
      
                        <hr />

                        <p style='text-align: center'>
                            If you wish to unsubscribe from our emails,
                            <a href='" . URL . MailsController::ROUTE . "/unsubscribe?em=$receiverMail' style='color: #4973b2; text-decoration: none'>click here</a>
                        </p>

                        <p style='font-size: 12px; color: #888; text-align: center'>
                            You are receiving this email because you signed up for " . APP_NAME . " updates. If you believe this email was sent in error, please disregard it.
                        </p>
                    </div>
                </body>
            </html>
        ";
    }
}
