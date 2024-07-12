<?php

namespace Securite\handlers;

use Securite\authenticator\SecuredActioner;

class RegisterSecuredActionHandler extends BaseHandler {
    private $secured_actioner;
    private $action;

    public function __construct(SecuredActioner $secured_actioner, string $action) {
        $this->secured_actioner = $secured_actioner;
        $this->action = $action;
    }

    public function handle(array $request) {
        $guid = $request['guid'];
        $this->secured_actioner->registerSecuredAction($guid, $this->action);
        return parent::handle($request);
    }
}
