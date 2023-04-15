<?php

// This file is incharge of handling cmds
namespace Minicli;

class App
{
    //to use the CliPrinter class
    protected $printer;
    protected $registry = [];

    public function __construct()
    {
        //assignment to new instance
        $this->printer = new CliPrinter();
    }

    //final output msg
    public function getPrinter()
    {
        //pass arg to printer class
        return $this->printer;
    }

    public function registryCmd($cmdName, $callable)
    {
        $this->registry[$cmdName] = $callable;
    }

    public function getCmd($cmdName)
    {
        //check if that cmd name is valid
        return $this->registry[$cmdName] ?? null;
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
        $cmd = $this->getCmd($cmdName);

        if ($cmd === null) {
            //return the msg
            $this->getPrinter()->displayMsg("ERROR: Command '$cmdName' not found.");
            exit;
        }

        //use a callback function to a specifc command ($cmd, i.e. help) with a argument $argv
        call_user_func($cmd, $argv);
    }
}
