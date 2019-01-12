<?php

require_once __DIR__ . '/bootstrap.php';

$file_handle = fopen('image.csv', 'r');
while (!feof($file_handle) ) {
    $line_of_text[] = fgetcsv($file_handle, 1024, ';');
}
fclose($file_handle);

foreach($line_of_text as $line) {
    \Database\addGrid((int)$line[0], (int)$line[1], $line[2], $line[3]);
}