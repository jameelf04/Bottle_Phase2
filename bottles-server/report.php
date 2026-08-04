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

$sql = "SELECT COUNT(*) AS total FROM reports WHERE userid = ? AND bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $bottleid);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

if ($row["total"] > 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "You already reported this bottle!";
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO reports(bottleid, userid) VALUES(?, ?)";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $bottleid, $user["userid"]);
$query->execute();

$reportThreshold = 3;

$sql = "SELECT COUNT(*) AS total FROM reports WHERE bottleid = ?";
$query = $mysql->prepare($sql);
$query->bind_param("i", $bottleid);
$query->execute();
$array = $query->get_result();
$row = $array->fetch_assoc();

if ($row["total"] >= $reportThreshold) {
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
}

$response = [];
$response["success"] = true;
$response["message"] = "Bottle reported!";
echo json_encode($response);

?>