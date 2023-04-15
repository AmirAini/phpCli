<?php

namespace Minicli;

class CommandRegistration
{
    protected $registry = [];

    public function registryCmd($cmdName, $callable)
    {
        $this->registry[$cmdName] = $callable;
    }

    public function getCmd($cmdName)
    {
        //check if that cmd name is valid
        return $this->registry[$cmdName] ?? null;
    }
}
