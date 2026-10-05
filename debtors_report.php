<?php

use objects\Tenants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Tenants.php') ? __DIR__ . '/Tenants.php' : __DIR__ . '/../objects/Tenants.php';

$database = new Database();
$db = $database->getConnection();

$tenants = new Tenants($db);
$stmt = $tenants->getDebtors();

if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(array("records" => $stmt->fetchAll(PDO::FETCH_ASSOC)), JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Задолжников нет"), JSON_UNESCAPED_UNICODE);
}
?>
