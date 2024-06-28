<?php

namespace Securite;
require_once '../autoloader.php';
class PATH
{
    public static function ROOT($file = "") {
        return $_SERVER["DOCUMENT_ROOT"] . '/' . $file;
    }

    public static function SECURED_ROOT($file = "") {
        return self::ROOT('../' . $file);
    }

    public static function CREDENTIALS($file = "") {
        return self::SECURED_ROOT('credentials/' . $file);
    }
}