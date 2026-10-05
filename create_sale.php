<?php

use objects\Sales;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Sales.php') ? __DIR__ . '/Sales.php' : __DIR__ . '/../objects/Sales.php';

$database = new Database();
$db = $database->getConnection();

$sale = new Sales($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->sale_date) &&
    !empty($data->product_code) &&
    !empty($data->product_name) &&
    !empty($data->quantity) &&
    !empty($data->amount))
{
    $sale->sale_date = $data->sale_date;
    $sale->product_code = $data->product_code;
    $sale->product_name = $data->product_name;
    $sale->quantity = $data->quantity;
    $sale->amount = $data->amount;

    if ($sale->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Продажа добавлена"), JSON_UNESCAPED_UNICODE);
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить продажу"), JSON_UNESCAPED_UNICODE);
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Неполные данные"), JSON_UNESCAPED_UNICODE);
}
?>