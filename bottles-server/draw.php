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

$expiryDays = 14;
$archiveMarkThreshold = 7;

$sql = "SELECT bottleid, 
        (SELECT COUNT(*) FROM marks m WHERE m.bottleid = bottles.bottleid) AS markcount,
        TIMESTAMPDIFF(DAY, last_activity, NOW()) AS days_inactive
        FROM bottles WHERE status = 'active'";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

while ($b = $array->fetch_assoc()){
    if ($b["markcount"] >= $archiveMarkThreshold){
        $sql2 = "UPDATE bottles SET status = 'archived' WHERE bottleid = ?";
        $query2 = $mysql->prepare($sql2);
        $query2->bind_param("i", $b["bottleid"]);
        $query2->execute();
    } elseif ($b["days_inactive"] >= $expiryDays){
        $sql2 = "UPDATE bottles SET status = 'expired' WHERE bottleid = ?";
        $query2 = $mysql->prepare($sql2);
        $query2->bind_param("i", $b["bottleid"]);
        $query2->execute();
    }
}

$sql = "SELECT b.*, 
        (SELECT COUNT(*) FROM draws d WHERE d.bottleid = b.bottleid) AS holdcount,
        (SELECT COUNT(*) FROM marks m WHERE m.bottleid = b.bottleid) AS markcount,
        TIMESTAMPDIFF(SECOND, b.time, NOW()) AS age_seconds
        FROM bottles b
        WHERE b.userid != ? 
        AND b.status = 'active'
        AND b.bottleid NOT IN (SELECT bottleid FROM draws WHERE userid = ?)";
$query = $mysql->prepare($sql);
$query->bind_param("ii", $user["userid"], $user["userid"]);
$query->execute();
$array = $query->get_result();

$candidates = [];
while ($row = $array->fetch_assoc()){
    $candidates[] = $row;
}

if (count($candidates) == 0) {
    $response = [];
    $response["success"] = false;
    $response["message"] = "No bottles available to draw right now!";
    echo json_encode($response);
    exit();
}

$totalWeight = 0;
foreach ($candidates as &$c){
    $rarityScore = 1 / (1 + $c["holdcount"]);
    $neglectScore = min($c["age_seconds"] / 86400, 30);
    $markScore = $c["markcount"] * 2;

    $c["score"] = 1 + $rarityScore + $neglectScore + $markScore;
    $totalWeight += $c["score"];
}
unset($c);

$randomPoint = mt_rand() / mt_getrandmax() * $totalWeight;
$runningTotal = 0;
$bottle = null;

foreach ($candidates as $c){
    $runningTotal += $c["score"];
    if ($randomPoint <= $runningTotal){
        $bottle = $c;
        break;
    }
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