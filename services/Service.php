<?php
namespace Securite\services;
use Securite\handlers\HandlerManager;
use Securite\StdLib;
use Securite\database\DataBase;

abstract class Service
{
//    const ALLOWED_ARGS = ["table", "record", "filter", "columns"];
    const ALLOWED_ARGS = ["email", "password", "otp", "action", "new_password"];
    protected $db;

    public function __construct(DataBase $db = null)
    {
        $this->db = $db ?: new DataBase();

        // verifie la methode de la requete
        $method = $_SERVER["REQUEST_METHOD"];
        if ($method != static::METHOD) {
            StdLib::sendResponse(405, [
                'error' => 'Method Not Allowed',
                'expected' => static::METHOD,
                'received' => $method
            ]);
            return;
        }

        $json_data = json_decode(file_get_contents('php://input'), true);

        if ($json_data == null) {
            StdLib::sendResponse(400, ['error' => 'Invalid JSON data']);
            return;
        }

        foreach ($json_data as $attribute => $value) {
            if (in_array($attribute, self::ALLOWED_ARGS)) {
                $this->{$attribute} = $value;
            }
        }

        if (!StdLib::validateRequiredArgs(static::NEEDED_ARGS, $this)) {
            StdLib::sendResponse(400, ['error' => 'Missing required arguments']);
            return;
        }

        $this->processRequest();
    }

    abstract function trig(): array;

    // traite la requete en appelant la chaine de handlers appropriee
    protected function processRequest()
    {
        $request = $this->trig();
        $type = $this->getHandlerType();
        $handler_chain = HandlerManager::handlerChains($type);
        $handler_chain->handle($request);
    }

    // determine le type de handler a partir du nom de la classe
    protected function getHandlerType(): string
    {
        $class_name = (new \ReflectionClass($this))->getShortName();
        return strtolower(str_replace('Service', '', $class_name));
    }
}