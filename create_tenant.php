<?php

use objects\Tenants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Tenants.php') ? __DIR__ . '/Tenants.php' : __DIR__ . '/../objects/Tenants.php';

$database = new Database();
$db = $database->getConnection();

$tenant = new Tenants($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->owner_name) && !empty($data->residents_count) && !empty($data->living_area)
    && !empty($data->apartment_type) && isset($data->rent_paid))
{
    $tenant->owner_name = $data->owner_name;
    $tenant->residents_count = $data->residents_count;
    $tenant->living_area = $data->living_area;
    $tenant->apartment_type = $data->apartment_type;
    $tenant->rent_paid = $data->rent_paid;

    if ($tenant->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Квартиросъемщик добавлен"), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно добавить (проверьте тип квартиры)"), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Неполные данные"), JSON_UNESCAPED_UNICODE);
}
?>
