<?php

namespace Minicli;

/**
 * Outsource  
 */

class CommandRegistry
{
    protected $commandPath;
    protected $namespaces = [];
    protected $defaultRegistry = [];

    public function __construct($commandPath)
    {
        $this->commandPath = $commandPath;

        //method is called after object instantiated
        $this->autoloadNamespaces();
    }

    public function autoloadNamespaces()
    {
        // /loop to automatically register all subdirectories in the commands directory as PHP namespaces
        foreach (glob($this->getCommandsPath() . '/*', GLOB_ONLYDIR) as $namespace_path) {
            $this->registerNamespace(basename($namespace_path));
        }
    }

    public function registerNamespace($commandNamespace)
    {
        //create new instance
        $namespace = new CommandNamespace($commandNamespace);
        $namespace->loadControllers($this->getCommandsPath());
        $this->namespaces[strtolower($commandNamespace)] = $namespace;
    }

    public function getNamespace($command)
    {
        return isset($this->namespaces[$command]) ? $this->namespaces[$command] : null;
    }

    public function getCommandsPath()
    {
        return $this->commandPath;
    }

    public function registerCommand($name, $callable)
    {
        $this->defaultRegistry[$name] = $callable;
    }

    public function getCommand($cmdName)
    {
        //check if that cmd name is valid
        return $this->defaultRegistry[$cmdName] ?? null;
    }

    public function getCallableController($command, $subcommand = null)
    {
        $namespace = $this->getNamespace($command);

        if ($namespace !== null) {
            return $namespace->getController($subcommand);
        }

        return null;
    }

    public function getCallable($command)
    {
        $single_command = $this->getCommand($command);
        if ($single_command === null) {
            throw new \Exception(sprintf("Command \"%s\" not found.", $command));
        }

        return $single_command;
    }
}
