<?php
namespace Securite\seclib;

require_once '../autoloader.php';

class Credentials {
    public $server_name;
    public $db_name;
    public $username;
    public $password;
    public $port;

    public function __construct($server_name, $db_name, $port, $username, $password) {
        $this->server_name = $server_name;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }
    public static function fromFile($filename) {
        $path = PATH::CREDENTIALS($filename);

        if (!file_exists($path)) {
            return null;
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }

        $json = json_decode($content, true);

        if (!is_array($json)) {
            return null;
        }

        return new Credentials(
            $json['server_name'] ?? null,
            $json['db_name'] ?? null,
            $json['port'] ?? null,
            $json['username'] ?? null,
            $json['password'] ?? null,
        );
    }
}