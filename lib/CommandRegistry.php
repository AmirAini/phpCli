<?php

namespace Minicli;

/**
 * Outsource  
 */

class CommandRegistry
{
    /**
     * The path to the directory containing the CLI command files.
     *
     * @var string
     */
    protected $commandPath;

    /**
     * An array of registered command namespaces.
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * An array of registered commands.
     *
     * @var array
     */
    protected $defaultRegistry = [];

    /**
     * Create a new CommandRegistry instance.
     *
     * @param string $commandPath The path to the directory containing the CLI command files.
     */
    public function __construct($commandPath)
    {
        $this->commandPath = $commandPath;

        // Register all subdirectories in the commands directory as PHP namespaces.
        $this->autoloadNamespaces();
    }

    /**
     * Register all subdirectories in the commands directory as PHP namespaces.
     */
    public function autoloadNamespaces()
    {
        foreach (glob($this->getCommandsPath() . '/*', GLOB_ONLYDIR) as $namespacePath) {
            $this->registerNamespace(basename($namespacePath));
        }
    }

    /**
     * Register a new command namespace.
     *
     * @param string $commandNamespace The name of the command namespace to register.
     */
    public function registerNamespace($commandNamespace)
    {
        $namespace = new CommandNamespace($commandNamespace);
        $namespace->loadControllers($this->getCommandsPath());
        $this->namespaces[strtolower($commandNamespace)] = $namespace;
    }

    /**
     * Get the CommandNamespace object for a given command.
     *
     * @param string $command The name of the command.
     *
     * @return CommandNamespace|null The CommandNamespace object for the given command, or null if not found.
     */
    public function getNamespace($command)
    {
        return isset($this->namespaces[$command]) ? $this->namespaces[$command] : null;
    }

    /**
     * Get the path to the directory containing the CLI command files.
     *
     * @return string The path to the directory containing the CLI command files.
     */
    public function getCommandsPath()
    {
        return $this->commandPath;
    }

    /**
     * Register a new command.
     *
     * @param string $name The name of the command.
     * @param mixed $callable The callable object to register.
     */
    public function registerCommand($name, $callable)
    {
        $this->defaultRegistry[$name] = $callable;
    }

    /**
     * Get the callable object for a given command.
     *
     * @param string $cmdName The name of the command.
     *
     * @return mixed|null The callable object for the given command, or null if not found.
     */
    public function getCommand($cmdName)
    {
        return $this->defaultRegistry[$cmdName] ?? null;
    }

    /**
     * This function returns a callable controller based on the given command and subcommand.
     * 
     * @param command A string representing the main command being executed.
     * @param subcommand The subcommand is an optional parameter that can be passed to the
     * `getCallableController` function. It represents a specific action or method within a command
     * that the user wants to execute. If a subcommand is provided, the function will attempt to
     * retrieve the corresponding controller for that subcommand within the specified
     * 
     * @return either the callable controller for the given command and subcommand, or null if the
     * namespace for the command is not found.
     */
    public function getCallableController($command, $subcommand = null)
    {
        $namespace = $this->getNamespace($command);

        if ($namespace !== null) {
            return $namespace->getController($subcommand);
        }

        return null;
    }

    /**
     * This PHP function retrieves a callable command and throws an exception if the command is not
     * found.
     * 
     * @param command The name of the command that we want to retrieve the callable function for.
     * 
     * @return the callable function for the given command.
     */
    public function getCallable($command)
    {
        $single_command = $this->getCommand($command);
        if ($single_command === null) {
            throw new \Exception(sprintf("Command \"%s\" not found.", $command));
        }

        return $single_command;
    }
}
