<?php

namespace Securite\handlers;

use Securite\authenticator\Identifier;

class CreateTempAccountHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $guid = $request['guid'];
        $password_data = $request['password_data'];
        $this->identifier->insertTempAccount($guid, $password_data);
        return parent::handle($request);
    }
}

