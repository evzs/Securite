<?php
namespace Securite\handlers;

abstract class BaseHandler implements Handler
{
    protected $next_handler;

    public function setNext(Handler $handler): Handler
    {
        $this->next_handler = $handler;
        return $handler;
    }

    public function handle(array $request)
    {
        if ($this->next_handler) {
            return $this->next_handler->handle($request);
        }

        return null;
    }
}