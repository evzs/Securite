<?php
namespace Securite\services;

use Securite\database\DataBase;

class SelectRecordService extends Service
{
    const METHOD = 'GET';
    const NEEDED_ARGS = ["table", "columns", "filter"];

    function execute()
    {
        $db = new DataBase();

        if (!isset($this->table) || !isset($this->columns) || !isset($this->filter)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments for reading records.']);
            return;
        }

        $result = $db->selectRecord($this->table, $this->columns, $this->filter);
        echo json_encode($result);
    }
}
