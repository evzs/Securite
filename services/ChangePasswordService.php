<?php
namespace Securite\services;

class ChangePasswordService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["email"];

    public function trig(): array
    {
        return [
            'email' => $this->email
        ];
    }
}
