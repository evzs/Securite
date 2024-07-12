<?php
namespace Securite\services;

namespace Securite\services;

use Securite\StdLib;

class UpdateRecordService extends Service
{
    const METHOD = 'PUT';
    const NEEDED_ARGS = ["table", "record", "filter"];

    public function execute()
    {
        $this->db->updateRecord($this->table, $this->record, $this->filter);
        StdLib::sendResponse(200, ['message' => 'Record updated successfully.']);
    }
}

