<?php
include(__DIR__ . "/database/connection.php");

if(isset($_POST["message"])){
    $message = $_POST["message"];
}else{
    $response = [];
    $response["success"] = false;
    $response["message"] = "Message is missing!";
    echo json_encode($response);
    exit();
}

if (strlen($message) == 0 || strlen($message) > 500) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "Message must be between 1 and 500 characters!";
    echo json_encode($response);
    exit();
}

$token = $_POST["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

$sql = "SELECT COUNT(*) AS total FROM bottles WHERE userid = ? AND DATE(time) = CURDATE()";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

$dailyThrowLimit = 5;

if ($row["total"] >= $dailyThrowLimit) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You've reached your daily throw limit!";
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO bottles(userid, message) VALUES(?, ?)";
$query = $mysql->prepare($sql);
$query->bind_param("is", $user["userid"], $message);
$query->execute();

$response = [];
$response["success"] = true;
$response["message"] = "Bottle thrown!";
echo json_encode($response);
?>