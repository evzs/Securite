<?php
namespace Securite\authenticator;

use Securite\database\DataBase;

class SecuredActioner {
    private $db;

    public function __construct(DataBase $db) {
        $this->db = $db;
    }

    //
    public function registerSecuredAction($guid, $action) {
        $this->db->addRecord('secured_actions', [
            'user_GUID' => $guid,
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    // generation de l'otp
    public function generateOTP() {
        return strtoupper(bin2hex(random_bytes(4)));
    }

    // insertion de l'otp dans la bdd
    public function insertOTP($guid, $otp, $webservice_id) {
        $this->db->addRecord('accounts_otp', [
            'user_GUID' => $guid,
            'OTP' => $otp,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'webservice_id' => $webservice_id
        ]);
    }

    //
    public function confirmSecuredAction($guid, $otp, $action) {
        return $this->validateOTP($guid, $otp, $action);
    }

    // supprime l'otp associe au GUID
    public function deleteOTP($guid) {
        $this->db->deleteRecord('accounts_otp', ['user_GUID' => $guid]);
    }

    // valide l'otp en accord avec l'action donnnee
    private function validateOTP($guid, $otp, $action) {
        $otp_record = $this->getOTPRecord($guid, $otp, $action);
        // debug perso
        error_log("Validating OTP for GUID: $guid, OTP: $otp, Action: $action");
        error_log("OTP record validation result: " . json_encode($otp_record));
        return !empty($otp_record);
    }

    // recupere l'otp de la db
    private function getOTPRecord($guid, $otp, $action) {
        $otp_record = $this->db->selectRecord('accounts_otp', ['OTP'], [
            'user_GUID' => $guid,
            'OTP' => $otp,
            'webservice_id' => $this->getWebserviceID($action)
        ]);
        // debug perso
        error_log("Fetching OTP record for GUID: $guid, OTP: $otp, Action: $action");
        error_log("Fetched OTP record: " . json_encode($otp_record));
        return $otp_record;
    }

    // associe l'id du webservice avec l'action
    private function getWebserviceID($action) {
        $webservice_map = [
            'signUp' => 1,
            'verifyAccount' => 2,
            'changePassword' => 3,
            'deleteAccount' => 4,
            'signIn' => 5,
            'signedIn' => 6,
            'signOut' => 7,
            'confirmPasswordChange' => 8,
            'confirmAccountDeletion' => 9
        ];

        return $webservice_map[$action] ?? null;
    }

    // transfert de tmp_accounts a accounts
    public function activateAccount($guid) {
        $temp_account = $this->getTempAccount($guid);
        $this->createAccount($temp_account);
        $this->deleteTempAccount($guid);
    }

    // supprime tous les registres associes au guid de l'utilisateur
    public function deleteUserRecords($guid)
    {
        $this->db->deleteRecord('accounts', ['user_GUID' => $guid]);
        $this->db->deleteRecord('tmp_accounts', ['user_GUID' => $guid]);
        $this->db->deleteRecord('accounts_otp', ['user_GUID' => $guid]);
        $this->db->deleteRecord('secured_actions', ['user_GUID' => $guid]);
        $this->db->deleteRecord('users', ['GUID' => $guid]);
    }

    // recupere le compte temporaire de la bdd
    private function getTempAccount($guid) {
        $temp_account = $this->db->selectRecord('tmp_accounts', ['user_GUID', 'pwd', 'salt', 'stretch', 'created_at'], ['user_GUID' => $guid]);
        // debug perso
        if (empty($temp_account)) {
            error_log("Temporary account not found for GUID: $guid");
            throw new \Exception("Temporary account not found for GUID: $guid");
        }
        error_log("Fetching temporary account for GUID: $guid");
        error_log("Fetched temporary account: " . json_encode($temp_account));
        return $temp_account[0];
    }

    // cree le nouveau compte en partant du compte tmp
    private function createAccount($temp_account) {
        $account_data = [
            'user_GUID' => $temp_account['user_GUID'],
            'pwd' => $temp_account['pwd'],
            'salt' => $temp_account['salt'],
            'stretch' => $temp_account['stretch'],
            'created_at' => $temp_account['created_at']
        ];

        $this->db->addRecord('accounts', $account_data);
    }

    // supprime le compte tmp de la db
    private function deleteTempAccount($guid) {
        $this->db->deleteRecord('tmp_accounts', ['user_GUID' => $guid]);
    }
}
