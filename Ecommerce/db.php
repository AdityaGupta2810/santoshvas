<?php

class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $database = 'santoshvastralay';
    private $password = '';
    private $result = null;

    function __construct() {
        $this->result = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->result->connect_error) {
            die("Connection failed: " . $this->result->connect_error);
        }
    }

    public function connect() {
        return $this->result;
    }
}

$dbInstance = new Database();
// global $db;
$db = $dbInstance->connect();  // Assigning to global variable separately



?>