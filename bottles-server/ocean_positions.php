<?php
include(__DIR__ . "/database/connection.php");
include(__DIR__ . "/drift.php");

$sql = "SELECT bottleid, origin_x, origin_y, seed, TIMESTAMPDIFF(SECOND, time, NOW()) AS age_seconds FROM bottles";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

$response = [];
$response["success"] = true;
$response["data"] = [];

while ($bottle = $array->fetch_assoc()){
    $position = getBottlePosition($bottle["origin_x"], $bottle["origin_y"], $bottle["seed"], $bottle["age_seconds"]);

    $response["data"][] = [
        "bottleid" => $bottle["bottleid"],
        "x" => $position["x"],
        "y" => $position["y"]
    ];
}

echo json_encode($response);
?>