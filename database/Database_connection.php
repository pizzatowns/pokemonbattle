<?php 

class Database_connection {
    function connect(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        // Create a connection\
        $connect = new mysqli($servername, $username, $password);

        if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
        }
        return $connect;
    }
}
