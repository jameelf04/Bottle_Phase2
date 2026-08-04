<?php
include(__DIR__ . "/database/connection.php");

$sql = "SELECT b.bottleid, b.message, b.time,
        TIMESTAMPDIFF(SECOND, b.time, NOW()) AS age_seconds,
        (SELECT COUNT(*) FROM marks m WHERE m.bottleid = b.bottleid) AS markcount
        FROM bottles b
        WHERE b.status = 'archived'
        ORDER BY b.time DESC";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

$response = [];
$response["success"] = true;
$response["data"] = [];

while ($bottle = $array->fetch_assoc()){
    $bottleData = [];
    $bottleData["bottleid"] = $bottle["bottleid"];
    $bottleData["message"] = $bottle["message"];
    $bottleData["markcount"] = $bottle["markcount"];
    $bottleData["age_seconds"] = $bottle["age_seconds"];

    $sql2 = "SELECT content FROM marks WHERE bottleid = ? ORDER BY time ASC";
    $query2 = $mysql->prepare($sql2);
    $query2->bind_param("i", $bottle["bottleid"]);
    $query2->execute();
    $array2 = $query2->get_result();

    $bottleData["marks"] = [];
    while ($mark = $array2->fetch_assoc()){
        $bottleData["marks"][] = $mark["content"];
    }

    $response["data"][] = $bottleData;
}

echo json_encode($response);
?>