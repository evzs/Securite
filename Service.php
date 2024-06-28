<?php
namespace Securite;
require_once 'autoloader.php';
abstract class Service
{
    const ALLOWED_ARGS = ["table", "record", "filter"];

    function __construct()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        if ($method != static::METHOD) {
            http_response_code(405);
            echo json_encode([
                'error' => 'Method Not Allowed',
                'expected' => static::METHOD,
                'received' => $method
            ]);
            return;
        }

        $json_data = json_decode(file_get_contents('php://input'), true);

        if ($json_data == null) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data.']);
            return;
        }

        foreach ($json_data as $attribute => $value) {
            if (in_array($attribute, self::ALLOWED_ARGS)) {
                $this->{$attribute} = $value;
            }
        }

        if (!StdLib::TestNeededArgs(static::NEEDED_ARGS, $this)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments.']);
            return;
        }

        $this->Trig();
    }

    abstract function Trig();
}
