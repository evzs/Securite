<?php
namespace Securite;

class Notifier {
    public function sendOTP($email, $otp) {
        echo json_encode(['email' => $email, 'otp' => $otp]);
    }
}
