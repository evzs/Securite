<?php

namespace Securite\handlers;

namespace Securite\handlers;

use Securite\Notifier;
use Securite\Sanitizer;

class SendOTPHandler extends BaseHandler {
    private $notifier;

    public function __construct(Notifier $notifier) {
        $this->notifier = $notifier;
    }

    public function handle(array $request) {
        $email = Sanitizer::sanitizeEmail($request['email']);
        $otp = $request['otp'];
        $this->notifier->sendOTP($email, $otp);
        return parent::handle($request);
    }
}
