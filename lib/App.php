<?php

// This file is incharge of handling cmds
namespace Minicli;

class App
{
    //to use the CliPrinter class
    protected $printer;
    protected $registrationCmd;

    public function __construct()
    {
        //assignment to new instance
        $this->printer = new CliPrinter();
        $this->registrationCmd = new CommandRegistration();
    }

    //final output msg
    public function getPrinter()
    {
        //pass arg to printer class
        return $this->printer;
    }

    //main passes to here then to another class
    public function registryCmd($cmd, $callable)
    {
        $this->registrationCmd->registryCmd($cmd, $callable);
    }

    //function from main page
    public function runCommand(array $argv)
    {
        if (isset($argv[1])) {
            $cmdName = $argv[1];
        } else {
            $cmdName = "help";
        }

        //call method
        $cmd = $this->registrationCmd->getCmd($cmdName);

        if ($cmd === null) {
            //return the msg
            $this->getPrinter()->displayMsg("ERROR: Command '$cmdName' not found.");
            exit;
        }

        //use a callback function to a specifc command ($cmd, i.e. help) with a argument $argv
        call_user_func($cmd, $argv);
    }
}
