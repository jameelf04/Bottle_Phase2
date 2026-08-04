<?php

function getOceanCondition(){
    $dayOfYear = date("z");
    $conditionIndex = $dayOfYear % 3;

    $conditions = ["calm", "storm", "doldrums"];
    return $conditions[$conditionIndex];
}

function getConditionSpeedMultiplier($condition){
    if ($condition == "storm") return 2.5;
    if ($condition == "doldrums") return 0.4;
    return 1;
}

function getConditionLimitModifier($condition){
    if ($condition == "storm") return -2;
    if ($condition == "doldrums") return 3;
    return 0;
}

function getBottlePosition($originX, $originY, $seed, $ageInSeconds){
    $condition = getOceanCondition();
    $speedMultiplier = getConditionSpeedMultiplier($condition);

    $speed = (0.0005 + ($seed % 100) / 200000) * $speedMultiplier;
    $radius = 5 + ($seed % 20);
    $phase = ($seed % 360) * (M_PI / 180);

    $angle = ($ageInSeconds * $speed) + $phase;

    $x = $originX + ($radius * cos($angle));
    $y = $originY + ($radius * sin($angle));

    $x = max(0, min(100, $x));
    $y = max(0, min(100, $y));

    return ["x" => $x, "y" => $y];
}

?>