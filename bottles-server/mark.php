<?php
include(__DIR__ . "/database/connection.php");

$token = $_POST["token"];

$sql = "SELECT * FROM users WHERE token = ?";
$query = $mysql->prepare($sql);
$query->bind_param("s", $token);
$query->execute();
$array = $query->get_result();
$user = $array->fetch_assoc();

if (!isset($_POST["bottleid"]) || !isset($_POST["content"])) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "Bottle id or content missing!";
    echo json_encode($response);
    exit();
}

$bottleid = $_POST["bottleid"];
$content = $_POST["content"];

$sql = "SELECT COUNT(*) AS total FROM marks WHERE userid = ? AND bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $bottleid);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

if ($row["total"] > 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You already marked this bottle!";
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO marks(bottleid, userid, content) VALUES(?, ?, ?)";
$query = $mysql->prepare($sql);
$query->bind_param("iis", $bottleid, $user["userid"], $content);
$query->execute();

$sql = "UPDATE bottles SET last_activity = NOW() WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();

$response = [];
$response["success"] = true;
$response["message"] = "Mark added!";
echo json_encode($response);

?>