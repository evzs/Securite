<?php
namespace Securite;
class StdLib
{
    public static function validateRequiredArgs($required_args, $class_instance): bool
    {
        foreach ($required_args as $arg) {
            if (!isset($class_instance->{$arg})) {
                echo json_encode(['error' => 'Missing ' . $arg . ' in JSON data']);
                return false;
            }
        }
        return true;
    }

    public static function sendResponse($code, $message) {
        http_response_code($code);
        echo json_encode($message);
    }
}


