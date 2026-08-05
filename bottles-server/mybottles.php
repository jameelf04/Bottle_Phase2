<?php
include(__DIR__ . "/database/connection.php");

$token = $_GET["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

$sql = "SELECT *, TIMESTAMPDIFF(SECOND, time, NOW()) AS age_seconds FROM bottles WHERE userid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();

$response = [];
$response["success"] = true;
$response["data"] = [];

while ($bottle = $array->fetch_assoc()) {
    $bottleData = [];
    $bottleData["bottleid"] = $bottle["bottleid"];
    $bottleData["message"] = $bottle["message"];
    $bottleData["age_seconds"] = $bottle["age_seconds"];

    $sql2 = "SELECT COUNT(*) AS total FROM draws WHERE bottleid = ?";
    $query2 = $mysql->prepare($sql2);
    $query2->bind_param("i", $bottle["bottleid"]);
    $query2->execute();
    $array2 = $query2->get_result();
    $row2 = $array2->fetch_assoc();
    $bottleData["holdcount"] = $row2["total"];

    $sql3 = "SELECT content FROM marks WHERE bottleid = ?";
    $query3 = $mysql->prepare($sql3);
    $query3->bind_param("i", $bottle["bottleid"]);
    $query3->execute();
    $array3 = $query3->get_result();
    $bottleData["marks"] = [];
    while ($mark = $array3->fetch_assoc()) {
        $bottleData["marks"][] = $mark["content"];
    }

    $response["data"][] = $bottleData;
}

echo json_encode($response);
?>