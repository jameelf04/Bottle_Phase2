<?php
include(__DIR__ . "/database/connection.php");

$token = $_GET["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

$sql = "SELECT COUNT(*) AS total FROM draws WHERE userid = ? AND DATE(time) = CURDATE()";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

$dailyDrawLimit = 5;

if ($row["total"] >= $dailyDrawLimit) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You've reached your daily draw limit!";
    echo json_encode($response);
    exit();
}

$sql = "SELECT COUNT(*) AS total FROM bottles WHERE userid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();
$throwCount = $row["total"];

if ($throwCount == 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You must throw a bottle before you can draw one!";
    echo json_encode($response);
    exit();
}

$sql = "SELECT * FROM bottles 
        WHERE userid != ? 
        AND bottleid NOT IN (SELECT bottleid FROM draws WHERE userid = ?)
        ORDER BY RAND() 
        LIMIT 1";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $user["userid"]);
$query->execute();
$array = $query->get_result();
$bottle = $array->fetch_assoc();

if ($bottle == null) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "No bottles available to draw right now!";
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO draws(userid, bottleid) VALUES(?, ?)";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $bottle["bottleid"]);
$query->execute();

$response = [];
$response["success"] = true;
$response["bottleid"] = $bottle["bottleid"];
$response["message"] = $bottle["message"];
echo json_encode($response);

?>