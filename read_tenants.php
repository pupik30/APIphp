<?php

use objects\Tenants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once file_exists(__DIR__ . '/Database.php') ? __DIR__ . '/Database.php' : __DIR__ . '/../config/Database.php';
include_once file_exists(__DIR__ . '/Tenants.php') ? __DIR__ . '/Tenants.php' : __DIR__ . '/../objects/Tenants.php';

$database = new Database();
$db = $database->getConnection();

$obj = new Tenants($db);
$stmt = $obj->readAll();

echo json_encode(array("records" => $stmt->fetchAll(PDO::FETCH_ASSOC)), JSON_UNESCAPED_UNICODE);
?>
