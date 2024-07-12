<?php
namespace Securite\services;

class VerifyAccountService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["email", "otp", "action"];

    public function trig(): array
    {
        return [
            'email' => $this->email,
            'otp' => $this->otp,
            'action' => $this->action
        ];
    }
}
