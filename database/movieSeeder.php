<?php

namespace Database;

use Dotenv\Dotenv;
use mysqli;

class movieSeeder
{

    public function seedData()
    {
        // Test connection
        $conn = $this->tableConnection();

        // Load CSV into PHP array
        $data = array_map(
            'str_getcsv',
            file('https://raw.githubusercontent.com/LearnDataSci/articles/master/Python%20Pandas%20Tutorial%20A%20Complete%20Introduction%20for%20Beginners/IMDB-Movie-Data.csv')
        );

        // Remove header row from CSV data
        array_shift($data);

        //check if table exists
        $tableName = "imdb_movies";
        $queryCheckTable = "SHOW TABLES LIKE '$tableName'";
        $result = $conn->query($queryCheckTable)->num_rows;

        if ($result > 0) {
            echo "Table already exists";
        } else {
            $queryCreateTable = "
            CREATE TABLE imdb_movies (
            Title VARCHAR(255),
            Genre VARCHAR(255),
            Description TEXT,
            Director VARCHAR(255),
            Actors TEXT,
            Year INT,
            Runtime INT,
            Rating FLOAT,
            Votes INT,
            Revenue FLOAT
            );";

            //check if table is created

            if ($conn->query($queryCreateTable) === TRUE) {
                echo "Table imdb_movies created successfully\n";
            } else {
                echo "Error creating table: " . $conn->error . "\n";
            }

            // Insert CSV data into MySQL table
            foreach ($data as $row) {
                $title = $conn->real_escape_string($row[1]);
                $genre = $conn->real_escape_string($row[2]);
                $description = str_replace("'", "''", $row[3]);
                $director = $conn->real_escape_string($row[4]);
                $actors = $conn->real_escape_string(str_replace("'", "''", $row[5]));
                $year = intval($row[6]);
                $runtime = intval($row[7]);
                $rating = floatval($row[8]);
                $votes = intval($row[9]);
                $revenue = floatval($row[10]);
                $insert_row = "INSERT INTO imdb_movies 
            (Title, Genre, Description, Director, Actors, Year, Runtime, Rating, Votes, Revenue) VALUES 
            ('$title', '$genre', '$description', '$director', '$actors', $year, $runtime, $rating, $votes, $revenue)";

                if ($conn->query($insert_row) === false) {
                    die("Error inserting row: " . $conn->error);
                }
            }
        }
        // Close MySQL connection
        $conn->close();
    }

    public function tableConnection()
    {
        require __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        // Connect to MySQL database 
        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_NAME'];

        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
