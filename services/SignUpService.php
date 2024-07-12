<?php
namespace Securite\services;

class SignUpService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["email", "password"];

    public function trig(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
