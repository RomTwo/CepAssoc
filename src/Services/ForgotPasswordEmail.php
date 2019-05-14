<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class ForgotPasswordEmail
{
    /**
     * Send an email to reset the user password. It contains a link for reset the user password
     *
     * @param $mail
     * @param $token
     */
    public function sendEmail($mail, $token)
    {

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername($_ENV['MAILER_MAIL'])
            ->setPassword($_ENV['MAILER_PASSWORD']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Réinitialisation mot de passe Association Cep Poitiers Gymnastique'))
            ->setFrom([$_ENV['MAILER_MAIL'] => 'Association Cep Poitiers Gymnastique'])
            ->setTo([$mail])
            ->setBody($this->msgHtml($token), 'text/html')
            ->setCharset('UTF-8');


        $result = $mailer->send($message);
    }

    /**
     * This is a body of the email (email for reset the user password)
     *
     * @param $token
     * @return string
     */
    private function msgHtml($token)
    {
        $msg = '<p>Réinitialisation de votre mot de passe en cliquant <a href="' . $_ENV['MAILER_URL'] . $token . '">ici</a></p>';

        return $msg;

    }
}