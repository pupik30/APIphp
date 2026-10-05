<?php

namespace objects;

use PDO;

class Sales
{
    private $conn;
    private $table = 'sales_accounting';

    public $id;
    public $sale_date;
    public $product_code;
    public $product_name;
    public $quantity;
    public $amount;

    public function __construct($db){
        $this->conn = $db;
    }

    // Товары, проданные после указанной даты (по алфавиту)
    public function getSalesAfterDate($date)
    {
        $query = "SELECT 
                    p.product_name,
                    SUM(s.quantity_sold) AS total_quantity,
                    p.unit_price AS price,
                    SUM(s.total_cost) AS total_revenue
                  FROM " . $this->table . " s
                  JOIN price_list p ON s.product_code = p.product_code
                  WHERE s.sale_date > :sale_date
                  GROUP BY p.product_code, p.product_name, p.unit_price
                  ORDER BY p.product_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sale_date', $date);
        $stmt->execute();

        return $stmt;
    }

    // Все записи учета реализации
    public function readAll()
    {
        $query = "SELECT sale_id, sale_date, product_code, product_name, quantity_sold, total_cost
                  FROM " . $this->table . " ORDER BY sale_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Создание записи о продаже (sale_id не автоинкрементный - берем MAX + 1)
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                    (sale_id, sale_date, product_code, product_name, quantity_sold, total_cost)
                  SELECT COALESCE(MAX(sale_id), 0) + 1, :sale_date, :product_code, :product_name, :quantity, :amount
                  FROM " . $this->table;

        $stmt = $this->conn->prepare($query);

        $this->sale_date = htmlspecialchars(strip_tags($this->sale_date));
        $this->product_code = htmlspecialchars(strip_tags($this->product_code));
        $this->product_name = htmlspecialchars(strip_tags($this->product_name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->amount = htmlspecialchars(strip_tags($this->amount));

        $stmt->bindParam(":sale_date", $this->sale_date);
        $stmt->bindParam(":product_code", $this->product_code);
        $stmt->bindParam(":product_name", $this->product_name);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":amount", $this->amount);

        return $stmt->execute();
    }
}
?>
