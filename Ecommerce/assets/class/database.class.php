<?php

class Database{

    private $host ='localhost';
    private $username = 'root';
    private $database  = 'santoshvastralay';

    private $password = '';
    private $result=null;

    function __construct(){
      $this->result= new mysqli($this->host, $this->username,  $this->password, $this->database); 
    }
   
     public function connect(){
        return $this->result;
    }

}

$db = new Database();
$db=$db->connect();