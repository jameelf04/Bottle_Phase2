<?php
include(__DIR__ . "/database/connection.php");

$bottleid = $_GET["bottleid"];

$sql = "SELECT message FROM bottles WHERE bottleid = ? AND status = 'active'";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();
$array = $query->get_result();
$bottle = $array->fetch_assoc();

$response = [];
if ($bottle) {
    $response["success"] = true;
    $response["message"] = $bottle["message"];
} else {
    $response["success"] = false;
    $response["message"] = "Bottle no longer available.";
}

echo json_encode($response);
?>