<?php


namespace App\Command\Movie;

use Database\movieSeeder;
use Minicli\CommandController;

class IndexController extends CommandController
{
    protected $movieSeeder;

    public function __construct()
    {
        $this->movieSeeder = new movieSeeder();
    }

    public function handle()
    {
        //connect to db
        $conn = $this->movieSeeder->tableConnection();

        $query = "SELECT * FROM imdb_movies LIMIT 300";

        $result = $conn->query($query);
        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        $this->getPrinter()->displayRecords($result);
    }
}
