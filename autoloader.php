<?php
function autoloader($class_name) {
    $root_namespace = 'Securite';

    if (str_starts_with($class_name, $root_namespace)) {
        $class_name = substr($class_name, strlen($root_namespace));
        $class_name = ltrim($class_name, '\\');
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

        $directories = [
            __DIR__,
            __DIR__ . '/services',
            __DIR__ . '/database',
            __DIR__ . '/seclib',
        ];

        foreach ($directories as $directory) {
            $file_path = $directory . '/' . $class_path;
            if (file_exists($file_path)) {
                require_once $file_path;
                return;
            }
        }

        error_log("Class file not found: " . $class_path);
    }
}

spl_autoload_register('autoloader');