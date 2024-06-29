<?php
namespace Securite;

require_once 'autoloader.php';

class UpdateRecordService extends Service
{
    const METHOD = 'PUT';
    const NEEDED_ARGS = ["table", "filter", "record"];

    function Trig()
    {
        $db = new DataBase();

        if (!isset($this->table) || !isset($this->record) || !isset($this->filter)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments for updating a record.']);
            return;
        }

        $db->updateRecord($this->table, $this->record, $this->filter);
        echo json_encode(['message' => 'Record updated successfully.']);
    }
}
