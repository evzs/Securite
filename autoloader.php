<?php
function autoloader($class_name) {
    $root_namespace = 'Securite';

    if (str_starts_with($class_name, $root_namespace)) {
        $class_name = substr($class_name, strlen($root_namespace));

        $class_name = ltrim($class_name, '\\');
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

        $root_dir = __DIR__ . '/' . $class_path;
        $src_dir = __DIR__ . '/src/' . $class_path;

        if (file_exists($root_dir)) {
            require_once $root_dir;
        } elseif (file_exists($src_dir)) {
            require_once $src_dir;
        } else {
            error_log("Class file not found: " . $class_path);
        }
    }
}

spl_autoload_register('autoloader');

//function autoloader($class_name) {
//    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
//    $class_file = __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.php';
//
//    if (file_exists($class_file)) {
//        require_once $class_file;
//    }
//}
//
//spl_autoload_register('autoloader');
