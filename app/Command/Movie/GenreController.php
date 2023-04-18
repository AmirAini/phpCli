<?php

namespace App\Command\Movie;

use Database\movieSeeder;
use Minicli\CommandController;

class GenreController extends CommandController
{
    protected $movieSeeder;

    public function __construct()
    {
        $this->movieSeeder = new movieSeeder();
    }

    public function handle()
    {
        $genre = $this->hasParam('filter') ? ucfirst($this->getParam('filter')) : 'Action';

        //connect to db
        $conn = $this->movieSeeder->tableConnection();

        $stmt = $conn->prepare("SELECT * FROM imdb_movies WHERE genre LIKE CONCAT('%', ?, '%') ORDER BY RAND() LIMIT 200");

        $stmt->bind_param("s", $genre);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();


        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        $this->getPrinter()->displayRecords($result);
    }
}
