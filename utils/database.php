<?php 
    class Database {
        public mysqli $database;

        public function __construct() {
            $this->database = new mysqli("0.0.0.0:3306", "root", "secret");
            if ($this->database->connect_error)
                die("failed to connect to database");
        }

        public function __destruct() {
            $this->database->close();
        }
    }

    $conn = new Database();
?>