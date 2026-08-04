<?php
include(__DIR__ . "/drift.php");

$response = [];
$response["success"] = true;
$response["condition"] = getOceanCondition();
echo json_encode($response);
?>