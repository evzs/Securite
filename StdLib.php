<?php
namespace Securite;
class StdLib
{
    static function TestNeededArgs($arr_of_args, $class_instance)
    {
        foreach ($arr_of_args as $arg) {
            if (!isset($class_instance->{$arg})) {
                echo json_encode(['error' => 'Missing ' . $arg . ' in JSON data']);
                return false;
            }
        }
        return true;
    }
}
