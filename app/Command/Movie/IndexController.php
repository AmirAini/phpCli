<?php

namespace App\Command\Movie;

use Database\movieSeeder;
use Minicli\CommandController;
use Minicli\QueryCommand;

class IndexController extends CommandController
{
    protected $movieSeeder;
    protected $queryCommand;

    public function __construct()
    {
        $this->queryCommand = new QueryCommand();
        $this->movieSeeder = new movieSeeder();
    }

    public function handle()
    {
        //connect to db
        $conn = $this->movieSeeder->tableConnection();
        $title = $this->hasParam('title') ? ucfirst($this->getParam('title')) : null;
        $genre = $this->hasParam('genre') ? ucfirst($this->getParam('genre')) : null;
        $year = $this->hasParam('year') ? $this->getParam('year') : null;
        $minRating = $this->hasParam('minRating') ? $this->getParam('rating') : null;


        //query
        $result = $this->queryCommand->query($conn, $title, $genre, $year, $minRating);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        $this->getPrinter()->displayRecords($result);
    }
}
