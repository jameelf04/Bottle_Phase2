<?php

function getBottlePosition($originX, $originY, $seed, $ageInSeconds){
    $speed = 0.0005 + ($seed % 100) / 200000;
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