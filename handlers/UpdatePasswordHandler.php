<?php
namespace Securite\handlers;

use Securite\authenticator\Identifier;

class UpdatePasswordHandler extends BaseHandler
{
    private $identifier;

    public function __construct(Identifier $identifier)
    {
        $this->identifier = $identifier;
    }

    public function handle(array $request)
    {
        $guid = $request['guid'];
        $new_password = $request['new_password'];

        try {
            $this->identifier->updatePassword($guid, $new_password);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        }

        return parent::handle($request);
    }
}
