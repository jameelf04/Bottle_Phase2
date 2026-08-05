<?php
include(__DIR__ . "/database/connection.php");

$token = $_POST["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

if (!isset($_POST["bottleid"])) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "Bottle id missing!";
    echo json_encode($response);
    exit();
}

$bottleid = $_POST["bottleid"];

$sql = "SELECT COUNT(*) AS total FROM keeps WHERE userid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $user["userid"]);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

if ($row["total"] > 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You've already used your permanent keep!";
    echo json_encode($response);
    exit();
}

$sql = "SELECT COUNT(*) AS total FROM bottles WHERE bottleid = ? AND userid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $bottleid, $user["userid"]);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

if ($row["total"] == 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You can only keep one of your own bottles!";
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO keeps(userid, bottleid) VALUES(?, ?)";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $bottleid);
$query->execute();

$sql = "UPDATE bottles SET status = 'kept' WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$response = [];
$response["success"] = true;
$response["message"] = "Bottle pulled from the ocean and kept forever.";
echo json_encode($response);

?>