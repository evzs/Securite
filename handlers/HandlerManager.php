<?php
namespace Securite\handlers;
use Securite\authenticator\Identifier;
use Securite\authenticator\SecuredActioner;
use Securite\database\DataBase;
use Securite\seclib\SHA512;
use Securite\Notifier;

class HandlerManager
{
    public static function handlerChains(string $type): Handler
    {
        switch ($type) {
            case 'signup':
                return self::signUpHC();
            case 'verifyaccount':
                return self::verifyAccountHC();
//            case 'signin':
//                return self::signInHC();
//            case 'signedin':
//                return self::signedInHC();
//            case 'signout':
//                return self::signOutHC();
//            case 'changepassword':
//                return self::changePasswordHC();
//            case 'deleteaccount':
//                return self::deleteAccountHC();
            default:
                throw new \Exception("Unknown handler type: $type");
        }
    }

    private static function signUpHC(): Handler {
        $db = new DataBase();
        $crypto = new SHA512();
        $secured_actioner = new SecuredActioner($db);
        $notifier = new Notifier();
        $identifier = new Identifier($db, $crypto, $secured_actioner, $notifier);

        $validateEmailHandler = new ValidateEmailHandler($identifier);
        $createUserHandler = $validateEmailHandler->setNext(new CreateUserHandler($identifier));
        $generatePasswordHandler = $createUserHandler->setNext(new GeneratePasswordHandler($identifier));
        $createTempAccountHandler = $generatePasswordHandler->setNext(new CreateTempAccountHandler($identifier));
        $registerSecuredActionHandler = $createTempAccountHandler->setNext(new RegisterSecuredActionHandler($secured_actioner, 'signUp'));
        $generateOTPHandler = $registerSecuredActionHandler->setNext(new GenerateOTPHandler($secured_actioner));
        $storeOTPHandler = $generateOTPHandler->setNext(new StoreOTPHandler($secured_actioner, 1));
        $sendOTPHandler = $storeOTPHandler->setNext(new SendOTPHandler($notifier));

        return $validateEmailHandler;
    }
    private static function verifyAccountHC(): Handler
    {
        $db = new DataBase();
        $crypto = new SHA512();
        $secured_actioner = new SecuredActioner($db);
        $notifier = new Notifier();
        $identifier = new Identifier($db, $crypto, $secured_actioner, $notifier);

        $userExistsHandler = new UserExistsHandler($identifier);
        $validateOTPHandler = $userExistsHandler->setNext(new ValidateOTPHandler($secured_actioner));
        $activateAccountHandler = $validateOTPHandler->setNext(new ActivateAccountHandler($secured_actioner));
        $deleteOTPHandler = $activateAccountHandler->setNext(new DeleteOTPHandler($secured_actioner));

        return $userExistsHandler;
    }
//
//    private static function signInHC(): Handler{
//
//    }
//
//    private static function signedInHC(): Handler{
//
//    }
//
//    private static function signOutHC(): Handler{
//
//    }
//
//    private static function changePasswordHC(): Handler{
//
//    }
//
//    private static function deleteAccountHC(): Handler{
//
//    }
}