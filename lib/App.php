<?php

namespace Minicli;

use Database\MovieSeeder;

/**
 * The main application class for the Minicli framework.
 */
class App
{
    /**
     * The CLI printer used for displaying messages.
     * @var CliPrinter
     */
    protected $printer;

    /**
     * The registry of available commands.
     * @var CommandRegistry
     */
    protected $command_registry;

    /**
     * The signature of the application.
     * @var string
     */
    protected $app_signature;

    /**
     * Initializes a new instance of the App class.
     */
    public function __construct()
    {
        $this->printer = new CliPrinter();
        $this->command_registry = new CommandRegistry(__DIR__ . '/../app/Command');
    }

    /**
     * Gets the CLI printer instance used by the application.
     * @return CliPrinter
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * Gets the signature of the application.
     * @return string
     */
    public function getSignature()
    {
        return $this->app_signature;
    }

    /**
     * Prints the application signature.
     */
    public function printSignature()
    {
        $this->getPrinter()->displayMsg(sprintf("usage: %s", $this->getSignature()));
    }

    /**
     * Sets the signature of the application.
     * @param string $app_signature The new signature to set.
     */
    public function setSignature($app_signature)
    {
        $this->app_signature = $app_signature;
    }

    /**
     * Registers a new command with the application.
     * @param string $name The name of the command.
     * @param callable $callable The callable that will handle the command.
     */
    public function registerCommand($name, $callable)
    {
        $this->command_registry->registerCommand($name, $callable);
    }

    /**
     * Runs a command with the given arguments.
     * @param array $argv The array of arguments to use when running the command.
     */
    public function runCommand(array $argv = [])
    {
        $input = new CommandCall($argv);

        if (count($input->args) < 2) {
            $this->printSignature();
            exit;
        }

        $controller = $this->command_registry->getCallableController($input->command, $input->subcommand);

        if ($controller instanceof CommandController) {
            $controller->boot($this);
            $controller->run($input);
            $controller->teardown();
            exit;
        }

        $this->runSingle($input);
    }

    /**
     * Runs a single command with the given input.
     * @param CommandCall $input The input to use when running the command.
     */
    protected function runSingle(CommandCall $input)
    {
        try {
            $callable = $this->command_registry->getCallable($input->command);
            call_user_func($callable, $input);
        } catch (\Exception $e) {
            $this->getPrinter()->displayMsg("ERROR: " . $e->getMessage());
            $this->printSignature();
            exit;
        }
    }
}
