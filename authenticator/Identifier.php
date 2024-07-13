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

    // verifie si l'utllisateur n'existe pas deja dans la bdd
    public function userDoesNotExist($email)
    {
        $existing_user = $this->db->selectRecord('users', ['GUID'], ['email' => $email]);
        if (!empty($existing_user)) {
            throw new \Exception("This email is taken");
        }
    }

    // ajoute un utilisateur a la bdd
    public function addUserToDatabase($email)
    {
        $this->db->addRecord('users', ['email' => $email]);
    }

    // recupere le guid utilisateur en fonction de son mail
    public function getUserGUID($email)
    {
        $new_user = $this->db->selectRecord('users', ['GUID'], ['email' => $email]);
        if (empty($new_user)) {
            throw new \Exception("User does not exist / Invalid credentials");
        }
        return $new_user[0]['GUID'];
    }

    // insertion utilisateur dans la bdd
    public function insertUser($email)
    {
        $this->userDoesNotExist($email);
        $this->addUserToDatabase($email);
        return $this->getUserGUID($email);
    }

    // insertion du compte temp dans la bdd
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

    // maj du mdp utilisateur dans la bdd
    public function updatePassword($guid, $new_password)
    {
        $password_data = $this->crypto->generatePassword($new_password, null, 1000);
        $this->db->updateRecord('accounts', [
            'pwd' => $password_data['secured_password'],
            'salt' => $password_data['salt'],
            'stretch' => $password_data['stretch']
        ], ['user_GUID' => $guid]);
    }
}
