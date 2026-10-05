<?php

namespace objects;

use PDO;

// Прейскурант
class PriceList
{
    private $conn;
    private $table = 'price_list';

    public $apartment_type;
    public $price_per_meter;
    public $utilities_per_person;

    public function __construct($db){
        $this->conn = $db;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " ORDER BY serial_no");
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (apartment_type, price_per_meter, utilities_per_person)
                  VALUES (:apartment_type, :price_per_meter, :utilities_per_person)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":apartment_type", htmlspecialchars(strip_tags($this->apartment_type)));
        $stmt->bindValue(":price_per_meter", $this->price_per_meter);
        $stmt->bindValue(":utilities_per_person", $this->utilities_per_person);

        return $stmt->execute();
    }
}
?>
