<?php
namespace Securite;
class Sanitizer
{
    public static function sanitizeEmail($email)
    {
        return strtolower(trim($email));
    }
}