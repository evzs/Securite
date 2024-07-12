<?php
namespace Securite\handlers;

use Securite\authenticator\Identifier;

class UserExistsHandler extends BaseHandler
{
    private $identifier;

    public function __construct(Identifier $identifier)
    {
        $this->identifier = $identifier;
    }

    public function handle(array $request)
    {
        $email = $request['email'];
        $guid = $this->identifier->getUserGUID($email);
        $request['guid'] = $guid;

        return parent::handle($request);
    }
}
