<?php

require_once __DIR__ . '/../../bootstrap.php';

$all = \Database\getCompleteGrid();
$events = \Database\getLastEvents();
$json = [
    'grid' => [],
    'events' => []
];
foreach($all as $grid) {
    $json['grid'][] = [
        'x' => $grid->x,
        'y' => $grid->y,
        'color' => $grid->color,
        'iteration' => $grid->iteration,
        'account' => $grid->sender,
        'price' => $grid->price
    ];
}
foreach($events as $ev) {
    $json['events'] = [
        'ts' => $ev->ts,
        'msg' => $ev->msg
    ];
}

header('Content-Type: application/json');

echo json_encode($json);
return;

?>