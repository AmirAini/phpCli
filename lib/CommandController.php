<?php

/**
 * Any class that inherits from CommandController will inherit the getApp method,
 * But required to implement a run method and handle the command execution.
 */

namespace Minicli;

abstract class CommandController
{
    protected $app;

    //functions that call this model needs to have run
    abstract public function run($argv);

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function getApp()
    {
        return $this->app;
    }
}
