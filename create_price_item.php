<?php

use objects\PriceList;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/PriceList.php') ? __DIR__ . '/PriceList.php' : __DIR__ . '/../objects/PriceList.php';

$database = new Database();
$db = $database->getConnection();

$item = new PriceList($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->apartment_type) && !empty($data->price_per_meter) && !empty($data->utilities_per_person))
{
    $item->apartment_type = $data->apartment_type;
    $item->price_per_meter = $data->price_per_meter;
    $item->utilities_per_person = $data->utilities_per_person;

    if ($item->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Тип квартиры добавлен в прейскурант"), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить"), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Неполные данные"), JSON_UNESCAPED_UNICODE);
}
?>
