<?php

namespace Minicli;

class CliPrinter
{
    public function out($message)
    {
        echo $message;
    }

    public function newLine()
    {
        //call function
        $this->out("\n");
    }

    public function displayMsg($msg)
    {
        $this->newLine();
        $this->out("$msg");
        $this->newLine();
    }
}
