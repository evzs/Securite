<?php
namespace Securite;

require_once 'Service.php';

class SelectRecordService extends Service
{
    const METHOD = 'GET';
    const NEEDED_ARGS = ["table", "filter"];

    function Trig()
    {
        $db = new DataBase();

        if (!isset($this->table) || !isset($this->filter)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments for reading records.']);
            return;
        }

        $result = $db->selectRecord($this->table, $this->filter);
        echo json_encode($result);
    }
}
