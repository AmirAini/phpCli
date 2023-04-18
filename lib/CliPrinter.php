<?php

namespace Minicli;

/**
 * The CliPrinter class provides utility methods for printing to the console.
 */
class CliPrinter
{
    /**
     * Outputs a message to the console.
     *
     * @param string $message The message to output.
     */
    public function out($message)
    {
        echo $message;
    }

    /**
     * Outputs a new line to the console.
     */
    public function newLine()
    {
        $this->out("\n");
    }

    /**
     * Displays a message on the console, surrounded by new lines.
     *
     * @param string $msg The message to display.
     */
    public function displayMsg($msg)
    {
        $this->newLine();
        $this->out("$msg");
        $this->newLine();
    }

    public function displayRecords($result)
    {
        while ($row = $result->fetch_assoc()) {
            $this->newLine();
            echo "Movie: " . $row["Title"] . ", Genre: " . $row["Genre"] . ", Released: " . $row["Year"] . ", Rating: " . $row["Rating"] . "/10";
            $this->newLine();
            echo "Decription: " . $row["Description"];
            $this->newLine();
        }
    }
}
