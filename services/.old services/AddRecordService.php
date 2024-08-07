<?php
namespace Securite\services;

use Securite\database\DataBase;


class AddRecordService extends Service
{
    const METHOD = 'POST';
    const NEEDED_ARGS = ["table", "record"];

    public function execute()
    {
        $db = new DataBase();

        if (!isset($this->table) || !isset($this->record)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required arguments for creating a record.']);
            return;
        }

        $db->addRecord($this->table, $this->record);
        echo json_encode(['message' => 'Record added successfully.']);
    }
}