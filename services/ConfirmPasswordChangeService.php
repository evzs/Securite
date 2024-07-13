<?php

namespace Securite\services;

class ConfirmPasswordChangeService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["email", "otp", "new_password"];

    public function trig(): array
    {
        return [
            'email' => $this->email,
            'otp' => $this->otp,
            'new_password' => $this->new_password,
        ];
    }
}
