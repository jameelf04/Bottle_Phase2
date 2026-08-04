<?php
include(__DIR__ . "/connection.php");

$sql = "SELECT bottleid FROM bottles WHERE origin_x = 0 AND origin_y = 0 AND seed = 0";
$query = $mysql->prepare($sql);
$query->execute();
$array = $query->get_result();

$count = 0;

while ($bottle = $array->fetch_assoc()){
    $originX = rand(0, 1000) / 10;
    $originY = rand(0, 1000) / 10;
    $seed = rand(1, 999999);

    $sql2 = "UPDATE bottles SET origin_x = ?, origin_y = ?, seed = ? WHERE bottleid = ?";
    $query2 = $mysql->prepare($sql2);
    $query2->bind_param("ddii", $originX, $originY, $seed, $bottle["bottleid"]);
    $query2->execute();

    $count++;
}

echo "Backfilled $count bottles with drift data.";
?>