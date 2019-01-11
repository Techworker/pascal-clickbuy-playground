<?php

require_once __DIR__ . '/../../bootstrap.php';

$accounts = $_GET['accounts'];
$all = \Database\getCompleteGrid();
$json = [];
foreach($all as $grid) {
    if(in_array($grid->sender, $accounts)) {
        $json[] = [
            'x' => $grid->x,
            'y' => $grid->y,
            'color' => $grid->color,
            'price' => $grid->price,
            'iteration' => $grid->iteration,
            'account' => $grid->sender
        ];
    }
}

header('Content-Type: application/json');

echo json_encode($json);
return;

?>