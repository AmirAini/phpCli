<?php

/**
 * A concrete controll to handle a specific command
 * If the command exists, the function would trigger this controller
 */

namespace App\Command;

use Minicli\CommandController;

//inherit the template of command controller with all it's method

class HelloController extends CommandController
{

    public function run($argv)
    {
        $name = isset($argv[2]) ? $argv[2] : 'Amir';
        //goes to App class then Goes to CliPrinter class for method
        $this->getApp()->getPrinter()->displayMsg("Hey $name");
    }
}
