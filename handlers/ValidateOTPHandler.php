<?php
namespace Securite\handlers;

use Securite\authenticator\SecuredActioner;

class ValidateOTPHandler extends BaseHandler
{
    private $secured_actioner;

    public function __construct(SecuredActioner $secured_actioner, $action)
    {
        $this->secured_actioner = $secured_actioner;
        $this->action = $action;
    }

    public function handle(array $request)
    {
        $guid = $request['guid'];
        $otp = $request['otp'];
        $valid_otp = $this->secured_actioner->confirmSecuredAction($guid, $otp, $this->action);

        if (!$valid_otp) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        }

        return parent::handle($request);
    }
}