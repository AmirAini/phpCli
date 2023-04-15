<?php

// This file is incharge of handling cmds
namespace Minicli;

class App
{
    //to use the CliPrinter class
    protected $printer;
    protected $commandRegistry;

    public function __construct()
    {
        //assignment to new instance
        $this->printer = new CliPrinter();
        $this->commandRegistry = new CommandRegistry();
    }

    //final output msg
    public function getPrinter()
    {
        //pass arg to printer class
        return $this->printer;
    }

    //register controller
    public function registerController($name, CommandController $controller)
    {
        $this->commandRegistry->registerController($name, $controller);
    }

    //main passes to here then to another class
    public function registerCommand($cmd, $callable)
    {
        $this->commandRegistry->registerCommand($cmd, $callable);
    }

    //function from main page
    public function runCommand(array $argv, $cmdName = 'help')
    {
        if (isset($argv[1])) {
            $cmdName = $argv[1];
        }

        if ($cmdName === null) {
            //return the msg
            $this->getPrinter()->displayMsg("ERROR: Command '$cmdName' not found.");
            exit;
        }

        //success
        try {
            call_user_func($this->commandRegistry->getCallable($cmdName), $argv);
        }
        //fail
        catch (\Exception $e) {
            $this->getPrinter()->displayMsg("ERROR: " . $e->getMessage());
            exit;
        }
    }
}
