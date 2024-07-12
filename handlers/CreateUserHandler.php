<?php

namespace Securite\handlers;

use Securite\authenticator\Identifier;

class CreateUserHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $email = $request['email'];
        $guid = $this->identifier->insertUser($email);
        $request['guid'] = $guid;
        return parent::handle($request);
    }
}
