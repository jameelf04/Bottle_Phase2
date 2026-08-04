<?php
include(__DIR__ . "/database/connection.php");

if (isset($_GET["token"]) && $_GET["token"] !== "") {
    $token = $_GET["token"];

    $sql = "SELECT * FROM users WHERE token = ?";
    $query = $mysql->prepare($sql);
    $query->bind_param("s", $token);
    $query->execute();
    $array = $query->get_result();
    $user = $array->fetch_assoc();

    $response = [];
    $response["success"] = true;
    $response["token"] = $token;
    $response["displayname"] = $user["display_name"];
    echo json_encode($response);

} else {
    $token = bin2hex(random_bytes(32));

    $adjectives = ["salt", "ash", "storm", "quiet", "deep"];
    $nouns = ["lantern", "signal", "harbor", "current", "tide"];
    $displayName = $adjectives[array_rand($adjectives)] . "-" . $nouns[array_rand($nouns)] . "-" . rand(1, 999);

    $originX = rand(0, 1000) / 10;
    $originY = rand(0, 1000) / 10;

    $sql = "INSERT INTO users(token, display_name, origin_x, origin_y) VALUES(?, ?, ?, ?)";
    $query = $mysql->prepare($sql);
    $query->bind_param("ssdd", $token, $displayName, $originX, $originY);
    $query->execute();

    $response = [];
    $response["success"] = true;
    $response["token"] = $token;
    $response["displayname"] = $displayName;
    echo json_encode($response);
}
?>