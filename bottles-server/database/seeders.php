<?php
include(__DIR__ . "/connection.php");
include(__DIR__ . "/factories.php");

$fakeUserCount = 20;
$fakeBottleCount = 200;

$fakeUserIds = [];

for ($i = 0; $i < $fakeUserCount; $i++){
    $token = fakeToken();
    $displayName = fakeDisplayName();

    $sql = "INSERT INTO users(token, display_name) VALUES(?, ?)";
    $query = $mysql->prepare($sql);
    $query->bind_param("ss", $token, $displayName);
    $query->execute();

    $fakeUserIds[] = $mysql->insert_id;
}

$fakeBottleIds = [];

for ($i = 0; $i < $fakeBottleCount; $i++){
    $userId = $fakeUserIds[array_rand($fakeUserIds)];
    $message = fakeMessage();
    $time = fakeTimeInPast(90);

    $sql = "INSERT INTO bottles(userid, message, time) VALUES(?, ?, ?)";
    $query = $mysql->prepare($sql);
    $query->bind_param("iss", $userId, $message, $time);
    $query->execute();

    $fakeBottleIds[] = $mysql->insert_id;
}

foreach ($fakeBottleIds as $bottleId){
    $markCount = rand(0, 6);
    $usedUserIds = [];

    for ($m = 0; $m < $markCount; $m++){
        $userId = $fakeUserIds[array_rand($fakeUserIds)];

        if (in_array($userId, $usedUserIds)){
            continue;
        }
        $usedUserIds[] = $userId;

        $content = fakeMarkContent();

        $sql = "INSERT INTO marks(bottleid, userid, content) VALUES(?, ?, ?)";
        $query = $mysql->prepare($sql);
        $query->bind_param("iis", $bottleId, $userId, $content);
        $query->execute();

        $sql2 = "INSERT INTO draws(userid, bottleid) VALUES(?, ?)";
        $query2 = $mysql->prepare($sql2);
        $query2->bind_param("ii", $userId, $bottleId);
        $query2->execute();
    }
}

echo "Seeded " . $fakeUserCount . " users and " . $fakeBottleCount . " bottles with scattered marks.";

?>