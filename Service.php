<?php
namespace Securite;

abstract class Service
{
    const ALLOWED_ARGS = ["table", "record", "filter", "columns"];

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

        $jsonData = json_decode(file_get_contents('php://input'), true);

        if ($jsonData == null) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        foreach ($jsonData as $attribute => $value) {
            if (in_array($attribute, self::ALLOWED_ARGS)) {
                $this->{$attribute} = $value;
            }
        }

        if (!StdLib::testNeededArgs(static::NEEDED_ARGS, $this)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments']);
            return;
        }

        $this->execute();
    }

    abstract function execute();
}
