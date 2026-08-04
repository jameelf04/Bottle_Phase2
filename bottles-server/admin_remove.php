<?php
include(__DIR__ . "/database/connection.php");

$adminKey = "jameel123";

if (!isset($_POST["key"]) || $_POST["key"] !== $adminKey) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "Invalid admin key!";
    echo json_encode($response);
    exit();
}

if (!isset($_POST["bottleid"])) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "Bottle id missing!";
    echo json_encode($response);
    exit();
}

$bottleid = $_POST["bottleid"];

$sql = "DELETE FROM draws WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$sql = "DELETE FROM marks WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$sql = "DELETE FROM reports WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$sql = "DELETE FROM bottles WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$response = [];
$response["success"] = true;
$response["message"] = "Bottle removed!";
echo json_encode($response);

?>