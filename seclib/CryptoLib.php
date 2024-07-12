<?php

namespace Securite\seclib;

abstract class CryptoLib
{
    public function generatePassword($password, $salt = null, $stretch = null, $salt_length = 32)
    {
        if (is_null($salt)) {
            $salt = $this->generateSalt($salt_length);
        }

        $secured_password = $this->hashPassword($password, $salt, $stretch);
        return [
            'secured_password' => $secured_password,
            'salt' => $salt,
            'stretch' => $stretch
        ];
    }

    public function comparePassword($password, $stored_hash, $salt, $stretch)
    {
        $computed_hash = $this->hashPassword($password, $salt, $stretch);
        return hash_equals($stored_hash, $computed_hash);
    }

    abstract protected function generateSalt($length);
    abstract protected function hashPassword($password, $salt, $stretch);
}