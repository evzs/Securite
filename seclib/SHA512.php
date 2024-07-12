<?php
namespace Securite\seclib;
class SHA512 extends CryptoLib
{

    public function generateSalt($length = 32)
    {
        $salt = random_bytes($length);
        return base64_encode($salt);
    }

    public function hashPassword($password, $salt, $stretch)
    {
        $hashed_password = $password . $salt;
        for ($i = 0; $i < $stretch; $i++) {
            $hashed_password = hash('sha512', $hashed_password);
        }
        return $hashed_password;
    }
}