<?php

namespace Securite\handlers;

use Securite\authenticator\SecuredActioner;

class GenerateOTPHandler extends BaseHandler {
    private $secured_actioner;

    public function __construct(SecuredActioner $secured_actioner) {
        $this->secured_actioner = $secured_actioner;
    }

    public function handle(array $request) {
        $otp = $this->secured_actioner->generateOTP();
        $request['otp'] = $otp;
        return parent::handle($request);
    }
}
