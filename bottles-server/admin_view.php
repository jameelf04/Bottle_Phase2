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

$sql = "SELECT bottles.bottleid, bottles.message, COUNT(reports.reportid) AS reportcount
        FROM bottles
        JOIN reports ON bottles.bottleid = reports.bottleid
        GROUP BY bottles.bottleid, bottles.message";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

$response = [];
$response["success"] = true;
$response["data"] = [];

while ($row = $array->fetch_assoc()) {
    $response["data"][] = $row;
}

echo json_encode($response);

?>