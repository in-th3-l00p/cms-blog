<?php 
    class Database {
        public mysqli $database;

        public function __construct() {
            $this->database = new mysqli("mysql", "root", "secret", "cms");
            if ($this->database->connect_error)
                die("failed to connect to database");
        }

        public function __destruct() {
            $this->database->close();
        }
    }

    function sanitizeInput(string $input) {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    $conn = new Database();
?>