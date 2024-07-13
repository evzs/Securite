<?php

namespace Securite\handlers;

interface Handler
{
    // prend un tableau de requetes en parametre
    public function handle(array $request);
}