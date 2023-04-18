<?php

namespace Minicli;

class QueryCommand
{
    public function query($conn, $title, $genre, $year, $minRating)
    {
        $query = "SELECT * FROM imdb_movies";
        $conditions = [];

        // Build the WHERE clause conditions
        if (!empty($genre)) {
            $conditions[] = "Genre LIKE CONCAT('%', ?, '%')";
        }

        if (!empty($year)) {
            $conditions[] = "Year = ?";
        }

        if (!empty($title)) {
            $conditions[] = "Title LIKE CONCAT('%', ?, '%')";
        }

        if (!empty($minRating)) {
            $conditions[] = "Rating >= ?";
        }

        // If there are any conditions, add the WHERE clause to the query
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add a LIMIT clause to the query
        $query .= " LIMIT 50";

        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind parameters
        $paramTypes = "";
        $params = [];

        if (!empty($genre)) {
            $paramTypes .= "s";
            $params[] = $genre;
        }

        if (!empty($year)) {
            $paramTypes .= "s";
            $params[] = $year;
        }

        if (!empty($title)) {
            $paramTypes .= "s";
            $params[] = $title;
        }

        if (!empty($minRating)) {
            $paramTypes .= "d";
            $params[] = $minRating;
        }

        if (!empty($paramTypes)) {
            $stmt->bind_param($paramTypes, ...$params);
        }

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        return $result;
    }
}
