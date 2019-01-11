<?php

require_once __DIR__ . '/bootstrap.php';

for($x = 1; $x <= 20; $x++) {
    for($y = 1; $y <= 20; $y++) {
        \Database\addGrid($x, $y);
    }
}