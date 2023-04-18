<?php

namespace Minicli;

/**
 * The CommandNamespace class represents a namespace for commands, and loads all controllers associated with that namespace.
 */
class CommandNamespace
{
    /**
     * The name of this command namespace.
     * 
     * @var string
     */
    protected $name;

    /**
     * An array of all controllers associated with this command namespace.
     * 
     * @var array
     */
    protected $controllers = [];

    /**
     * Constructor for the CommandNamespace class.
     * 
     * @param string $name The name of this command namespace.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of this command namespace.
     * 
     * @return string The name of this command namespace.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Loads all controllers associated with this command namespace.
     * 
     * @param string $commands_path The path to the directory containing the controllers.
     * 
     * @return array An array of all controllers associated with this command namespace.
     */
    public function loadControllers($commands_path)
    {
        foreach (glob($commands_path . '/' . $this->getName() . '/*Controller.php') as $controller_file) {
            $this->loadCommandMap($controller_file);
        }

        return $this->getControllers();
    }

    /**
     * Gets an array of all controllers associated with this command namespace.
     * 
     * @return array An array of all controllers associated with this command namespace.
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * Gets a specific controller associated with this command namespace.
     * 
     * @param string $command_name The name of the command to get the controller for.
     * 
     * @return CommandController|null The controller associated with the given command, or null if the command does not exist.
     */
    public function getController($command_name)
    {
        return isset($this->controllers[$command_name]) ? $this->controllers[$command_name] : null;
    }

    /**
     * Loads a single controller and adds it to the list of controllers associated with this command namespace.
     * 
     * @param string $controller_file The file path for the controller to load.
     * 
     * @return void
     */
    protected function loadCommandMap($controller_file)
    {
        $filename = basename($controller_file);

        $controller_class = str_replace('.php', '', $filename);
        $command_name = strtolower(str_replace('Controller', '', $controller_class));
        $full_class_name = sprintf("App\\Command\\%s\\%s", $this->getName(), $controller_class);
        echo($full_class_name);

        /** @var CommandController $controller */
        $controller = new $full_class_name();
        $this->controllers[$command_name] = $controller;
    }
}
