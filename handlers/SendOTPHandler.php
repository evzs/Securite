<?php

namespace Securite\handlers;

namespace Securite\handlers;

use Securite\Notifier;

class SendOTPHandler extends BaseHandler {
    private $notifier;

    public function __construct(Notifier $notifier) {
        $this->notifier = $notifier;
    }

    public function handle(array $request) {
        $email = $request['email'];
        $otp = $request['otp'];
        $this->notifier->sendOTP($email, $otp);

        return parent::handle($request);
    }
}
