<?php

namespace Securite\handlers;

use Securite\authenticator\Identifier;

class ValidateEmailHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $email = $request['email'];
        $this->identifier->userDoesNotExist($email);
        return parent::handle($request);
    }
}
