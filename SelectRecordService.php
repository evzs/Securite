<?php
namespace Securite;

require_once 'autoloader.php';
class SelectRecordService extends Service
{
    const METHOD = 'GET';

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
