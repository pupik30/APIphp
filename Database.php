<?php
class Database
{
    private $host = "127.127.126.9";//----------------------------------------------------127.127.126.10
    private $username = "root";
    private $password = "";
    private $database = "db_rent";
    private $conn;

    public function getConnection(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>