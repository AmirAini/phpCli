<?php

namespace Database;

class DbConnection
{
    function OpenCon()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "test";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $db);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";
        return $conn;
    }

    function CloseCon($conn)
    {
        $conn->close();
    }
}
