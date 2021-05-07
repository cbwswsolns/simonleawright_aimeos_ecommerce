<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ReCaptcha\ReCaptcha;

class Captcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute [field under validation]
     * @param mixed  $value     [value of field]
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));

        $resp = $recaptcha->setExpectedHostname(env('MAIL_EXPECTED_HOST'))
            ->setExpectedAction('contact')
            ->setScoreThreshold(0.5)
            ->verify($value, $_SERVER['REMOTE_ADDR']);

        return $resp->isSuccess();
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'CAPTCHA failed!';
    }
}
