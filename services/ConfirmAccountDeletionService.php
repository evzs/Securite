<?php
namespace Securite\services;

class ConfirmAccountDeletionService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["email", "otp"];

    public function trig(): array
    {
        return [
            'email' => $this->email,
            'otp' => $this->otp,
        ];
    }
}
