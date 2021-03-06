<?php

namespace Kontenta\KontourSupport\Auth\Passwords;

use Kontenta\KontourSupport\Auth\Notifications\ResetPassword as AdminResetPasswordNotification;
use \Illuminate\Auth\Passwords\CanResetPassword as BaseCanResetPassword;

trait CanResetPassword
{
    use BaseCanResetPassword;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
