<?php
namespace Securite\handlers;

abstract class BaseHandler implements Handler
{
    protected $next_handler;

    // permet de definir le prochain handler dans la chaine
    public function setNext(Handler $handler): Handler
    {
        $this->next_handler = $handler;
        return $handler;
    }

    // traite la requete et passe au prochain handler s'il existe
    public function handle(array $request)
    {
        if ($this->next_handler) {
            return $this->next_handler->handle($request);
        }

        return null;
    }
}