<?php

namespace App\Services;

class CaptchaCheck
{
    /**
     * Check if the token is correct (this system is use for the registration on the web site and when the user has forgotten his password)
     * @param string $captcha
     * @return bool
     */
    public function captchaIsValid(string $captcha): bool
    {
        $recaptcha = file_get_contents($_ENV['CAPTCHA_URL_CHECK'] . '?secret=' . $_ENV['CAPTCHA_KEY_SECRET'] . '&response=' . $captcha);
        $recaptcha = json_decode($recaptcha);

        if ($recaptcha->success) {
            return $recaptcha->score >= 0.5 && $recaptcha->action === 'register' ? true :
                ($recaptcha->score >= 0.6 && $recaptcha->action === 'forgot' ? true : false);
        } else {
            return false;
        }
    }
}