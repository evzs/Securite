<?php
namespace Securite;

require_once 'autoloader.php';

class DeleteRecordService extends Service
{
    const METHOD = 'DELETE';

    function Trig()
    {
        $db = new DataBase();

        if (!isset($this->table) || !isset($this->filter)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments for deleting a record.']);
            return;
        }

        $db->deleteRecord($this->table, $this->filter);
        echo json_encode(['message' => 'Record deleted successfully.']);
    }
}