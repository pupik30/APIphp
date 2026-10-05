<?php

use objects\Sales;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Sales.php') ? __DIR__ . '/Sales.php' : __DIR__ . '/../objects/Sales.php';

$database = new Database();
$db = $database->getConnection();

$sales = new Sales($db);

// Получаем дату из параметров запроса
$input_date = isset($_GET['date']) ? $_GET['date'] : die(json_encode(array("message" => "Не указана дата")));

// Получаем данные о продажах после указанной даты
$stmt = $sales->getSalesAfterDate($input_date);
$num = $stmt->rowCount();

if($num > 0){
    $sales_arr = array();
    $sales_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $sales_item = array(
            "product_name" => $product_name,
            "total_quantity" => $total_quantity,
            "price" => $price,
            "total_revenue" => $total_revenue
        );
        array_push($sales_arr["records"], $sales_item);
    }

    http_response_code(200);
    echo json_encode($sales_arr, JSON_UNESCAPED_UNICODE);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "Товары не найдены"), JSON_UNESCAPED_UNICODE);
}
?>