<?php

namespace Minicli;


/**
 * The CommandCall class represents a command and its arguments.
 */
class CommandCall
{
    /**
     * The command name.
     *
     * @var string|null
     */
    public $command;

    /**
     * The subcommand name.
     *
     * @var string
     */
    public $subcommand;

    /**
     * The command line arguments.
     *
     * @var array
     */
    public $args = [];

    /**
     * The command line parameters.
     *
     * @var array
     */
    public $params = [];

    /**
     * CommandCall constructor.
     *
     * @param array $argv The command line arguments.
     */
    public function __construct(array $argv)
    {
        $this->args = $argv;
        $this->command = isset($argv[1]) ? $argv[1] : null;
        $this->subcommand = isset($argv[2]) ? $argv[2] : 'default';

        $this->loadParams($argv);
    }

    /**
     * Loads command line parameters into the $params property.
     *
     * @param array $args The command line arguments.
     *
     * @return void
     */
    protected function loadParams(array $args)
    {
        foreach ($args as $arg) {
            $pair = explode('=', $arg);
            if (count($pair) == 2) {
                $this->params[$pair[0]] = $pair[1];
            }
        }
    }

    /**
     * Checks if a parameter exists in the $params property.
     *
     * @param string $param The parameter name.
     *
     * @return bool True if the parameter exists, false otherwise.
     */
    public function hasParam($param)
    {
        return isset($this->params[$param]);
    }

    /**
     * Gets the value of a parameter from the $params property.
     *
     * @param string $param The parameter name.
     *
     * @return mixed|null The parameter value, or null if the parameter does not exist.
     */
    public function getParam($param)
    {
        return $this->hasParam($param) ? $this->params[$param] : null;
    }
}
