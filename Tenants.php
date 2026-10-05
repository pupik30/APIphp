<?php

namespace objects;

use PDO;

// Квартиросъемщик
class Tenants
{
    private $conn;
    private $table = 'tenants';

    public $owner_name;
    public $residents_count;
    public $living_area;
    public $apartment_type;
    public $rent_paid;

    public function __construct($db){
        $this->conn = $db;
    }

    // Ведомость задолжников: задолженность = начислено - внесено
    public function getDebtors()
    {
        $query = "SELECT 
                    serial_no,
                    owner_name,
                    apartment_type,
                    apartment_cost,
                    rent_paid,
                    apartment_cost - rent_paid AS debt
                  FROM " . $this->table . "
                  WHERE rent_paid < apartment_cost
                  ORDER BY owner_name ASC, serial_no ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " ORDER BY serial_no");
        $stmt->execute();
        return $stmt;
    }

    // Стоимость квартиры считается по прейскуранту:
    // жилая площадь * цена метра + проживающие * коммунальные услуги на человека
    public function create()
    {
        $query = "INSERT INTO " . $this->table . "
                    (owner_name, residents_count, living_area, apartment_type, apartment_cost, rent_paid)
                  SELECT :owner_name, :residents_count, :living_area, p.apartment_type,
                         :living_area2 * p.price_per_meter + :residents_count2 * p.utilities_per_person,
                         :rent_paid
                  FROM price_list p
                  WHERE p.apartment_type = :apartment_type";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":owner_name", htmlspecialchars(strip_tags($this->owner_name)));
        $stmt->bindValue(":residents_count", $this->residents_count);
        $stmt->bindValue(":residents_count2", $this->residents_count);
        $stmt->bindValue(":living_area", $this->living_area);
        $stmt->bindValue(":living_area2", $this->living_area);
        $stmt->bindValue(":apartment_type", htmlspecialchars(strip_tags($this->apartment_type)));
        $stmt->bindValue(":rent_paid", $this->rent_paid);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}
?>
