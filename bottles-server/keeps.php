<?php
include(__DIR__ . "/database/connection.php");

$token = $_GET["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

$sql = "SELECT b.bottleid, b.message FROM keeps k
        JOIN bottles b ON k.bottleid = b.bottleid
        WHERE k.userid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();

$response = [];
$response["success"] = true;
$response["data"] = [];

while ($bottle = $array->fetch_assoc()){
    $response["data"][] = $bottle;
}

echo json_encode($response);
?>