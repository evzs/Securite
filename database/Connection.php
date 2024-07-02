<?php
namespace Securite\database;
use Securite\seclib\Credentials;


class Connection {
    public $pdo;
    public $dbh;
    public $db_name;

    public function __construct(Credentials $credentials = null) {
        if (!isset($credentials)) $credentials = Credentials::fromFile('db_credentials.json');

        $this->db_name = $credentials->db_name;
        $this->pdo = self::PDO($credentials);
        $this->dbh = self::PDO($credentials, $this->db_name);
    }

    static function PDO(Credentials $credentials, $db_name = null)
    {
        if (isset($credentials->db_name)) { 
            $dsn = "mysql:host={$credentials->server_name};dbname={$credentials->db_name};port={$credentials->port}";
        } else {
            $dsn = "mysql:host={$credentials->server_name};port={$credentials->port}";
        }

        try {
            $dbh = new \PDO($dsn, $credentials->username, $credentials->password);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e;
        }

        return $dbh;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
