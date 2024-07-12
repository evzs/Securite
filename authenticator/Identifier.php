<?php
namespace Securite\authenticator;

use Securite\database\DataBase;
use Securite\seclib\SHA512;
use Securite\Notifier;

class Identifier
{
    private $db;
    public $crypto;
    private $secured_actioner;
    private $notifier;

    public function __construct(DataBase $db, SHA512 $crypto, SecuredActioner $secured_actioner, Notifier $notifier) {
        $this->db = $db;
        $this->crypto = $crypto;
        $this->secured_actioner = $secured_actioner;
        $this->notifier = $notifier;
    }

    public function userDoesNotExist($email)
    {
        $existing_user = $this->db->selectRecord('users', ['GUID'], ['email' => $email]);
        if (!empty($existing_user)) {
            throw new \Exception("This email is taken");
        }
    }

    public function addUserToDatabase($email)
    {
        $this->db->addRecord('users', ['email' => $email]);
    }

    public function getUserGUID($email) {
        $new_user = $this->db->selectRecord('users', ['GUID'], ['email' => $email]);
        if (empty($new_user)) {
            throw new \Exception("Failed to retrieve GUID of new user");
        }
        return $new_user[0]['GUID'];
    }

    public function insertUser($email)
    {
        $this->userDoesNotExist($email);
        $this->addUserToDatabase($email);
        return $this->getUserGUID($email);
    }

    public function insertTempAccount($guid, $password_data)
    {
        $record = [
            'user_GUID' => $guid,
            'pwd' => $password_data['secured_password'],
            'salt' => $password_data['salt'],
            'stretch' => $password_data['stretch']
        ];

        $this->db->addRecord('tmp_accounts', $record);
    }

//    public function verifyAccount($email, $otp) {
//            $guid = $this->getUserGUID($email);
//
//            $isValidOTP = $this->secured_actioner->confirmSecuredAction($guid, $otp, 'signUp');
//
//            $this->secured_actioner->deleteOTP($guid);
//
//            if ($isValidOTP) {
//                return json_encode(['status' => 'success', 'message' => "You're all good"]);
//            } else {
//                return json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
//            }
//    }



//    public function createTempAccount($email, $password)
//    {
//        $guid = $this->insertUser($email);
//        $password_data = $this->crypto->generatePassword($password, null, 1000);
//        $this->insertTempAccount($guid, $password_data);
//        $this->secured_actioner->registerSecuredAction($guid, 'signup');
//        $otp = $this->secured_actioner->generateOTP();
//        $this->secured_actioner->insertOTP($guid, $otp, 1);
//        $this->notifier->sendOTP($email, $otp);
//
//        return $guid;
//    }
}
