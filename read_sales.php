<?php

use objects\Sales;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Sales.php') ? __DIR__ . '/Sales.php' : __DIR__ . '/../objects/Sales.php';

$database = new Database();
$db = $database->getConnection();

$obj = new Sales($db);
$stmt = $obj->readAll();

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array("records" => $records), JSON_UNESCAPED_UNICODE);
?>
