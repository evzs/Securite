<?php
namespace Securite\handlers;

use Securite\authenticator\SecuredActioner;

class DeleteUserRecordsHandler extends BaseHandler
{
    private $secured_actioner;

    public function __construct(SecuredActioner $secured_actioner)
    {
        $this->secured_actioner = $secured_actioner;
    }

    public function handle(array $request)
    {
        $guid = $request['guid'];
        $this->secured_actioner->deleteUserRecords($guid);

        return parent::handle($request);
    }
}
