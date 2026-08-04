<?php
include(__DIR__ . "/database/connection.php");
include(__DIR__ . "/drift.php");

$sql = "SELECT bottleid, origin_x, origin_y, seed, TIMESTAMPDIFF(SECOND, time, NOW()) AS age_seconds FROM bottles ORDER BY bottleid DESC LIMIT 5";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

$response = [];

while ($bottle = $array->fetch_assoc()){
    $position = getBottlePosition($bottle["origin_x"], $bottle["origin_y"], $bottle["seed"], $bottle["age_seconds"]);

    $response[] = [
        "bottleid" => $bottle["bottleid"],
        "origin" => ["x" => $bottle["origin_x"], "y" => $bottle["origin_y"]],
        "current" => $position,
        "age_seconds" => $bottle["age_seconds"]
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>