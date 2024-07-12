<?php

namespace Securite\handlers;

namespace Securite\handlers;

use Securite\authenticator\Identifier;

class GeneratePasswordHandler extends BaseHandler {
    private $identifier;

    public function __construct(Identifier $identifier) {
        $this->identifier = $identifier;
    }

    public function handle(array $request) {
        $password = $request['password'];
        $password_data = $this->identifier->crypto->generatePassword($password, null, 1000);
        $request['password_data'] = $password_data;
        return parent::handle($request);
    }
}
