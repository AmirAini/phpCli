<?php

namespace Minicli;

/**
 * With CommandController we've implemented a method named getCallable.
 * Responsible for figuring out which code should be called when a command is invoked. 
 */

class CommandRegistry
{
    protected $registry = [];
    protected $controllers = [];

    public function registerController($commandName, CommandController $controller)
    {
        $this->controllers = [ $commandName => $controller ];
    }

    public function registerCommand($name, $callable)
    {
        $this->registry[$name] = $callable;
    }

    public function getController($commandName)
    {

        return $this->controllers[$commandName] ?? null;
    }

    public function getCommand($cmdName)
    {
        //check if that cmd name is valid
        return $this->registry[$cmdName] ?? null;
    }

    public function getCallable($commandName)
    {
        $controller = $this->getController($commandName);

        if ($controller instanceof CommandController) {
            return [$controller, 'run'];
        }

        $command = $this->getCommand($commandName);

        if ($command === null) {
            throw new \Exception("Command '$commandName' not found.");
        }

        return $command;
    }
}
