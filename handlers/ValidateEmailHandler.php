<?php

namespace Securite\handlers;

use Securite\authenticator\Identifier;
use Securite\Sanitizer;

class ValidateEmailHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $email = Sanitizer::sanitizeEmail($request['email']);
        $this->identifier->userDoesNotExist($email);
        $request['email'] = $email;

        return parent::handle($request);
    }
}
