<?php

namespace Securite\handlers;

use Securite\authenticator\Identifier;
use Securite\Sanitizer;

class CreateUserHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $email = Sanitizer::sanitizeEmail($request['email']);
        $guid = $this->identifier->insertUser($email);
        $request['guid'] = $guid;
        $request['email'] = $email;
        return parent::handle($request);
    }
}
