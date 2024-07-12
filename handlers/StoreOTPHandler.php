<?php

namespace Securite\handlers;

use Securite\authenticator\SecuredActioner;

class StoreOTPHandler extends BaseHandler {
    private $secured_actioner;
    private $webservice_id;

    public function __construct(SecuredActioner $secured_actioner, int $webservice_id) {
        $this->secured_actioner = $secured_actioner;
        $this->webservice_id = $webservice_id;
    }

    public function handle(array $request) {
        $guid = $request['guid'];
        $otp = $request['otp'];
        $this->secured_actioner->insertOTP($guid, $otp, $this->webservice_id);
        return parent::handle($request);
    }
}

