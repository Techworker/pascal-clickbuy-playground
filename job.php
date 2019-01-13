<?php

require_once __DIR__ . '/bootstrap.php';

function hexToStr($hex){
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2){
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

do {
    $allPendings = \Pascal\getPendings();

    $allGrid = \Database\getCompleteGrid();

    $checked = [];
    foreach($allPendings as $op) {
        foreach($allGrid as $g) {
            //if(isset($checked[$g->sender])) {
            //    continue;
            //}
            //$checked[$g->sender] = true;
            updateOp($op, $g->sender);
        }
    }


    foreach($allGrid as $g) {
        if(isset($checked[$g->sender])) {
            continue;
        }
        $checked[$g->sender] = true;
        $accountOps = \Pascal\rpc('getaccountoperations', ['account' => $g->sender, 'depth' => 10]);
        foreach($accountOps as $op) {
            updateOp($op, $g->sender);
        }
    }

    calculateGrid();
    sleep(2);
} while(true);

function calculateGrid()
{
    $allGrid = \Database\getCompleteGrid();
    foreach($allGrid as $grid) {
        // fetch latest op
        $op = \Database\getLatestOp($grid->id);
        if ($op !== false) {
            if ($grid->ophash !== $op->ophash) {
                \Database\addEvent('Account ' . $op->sender . ' bought ' . $op->x . ':' . $op->y . ' for ' . ($grid->price/10000) . 'PASC');
                $grid->color = $op->color;
                $grid->sender = $op->sender;
                $grid->ophash = $op->ophash;
                $grid->price = $grid->price * 2;
                $grid->iteration++;
                $grid->save();
            }
        }
    }
}

function updateOp($operation, $r)
{
    foreach($operation['receivers'] as $idx => $receiver)
    {
        if((int)$receiver['account'] == $r && $receiver['payload'] !== '') {
            $pl = json_decode(hexToStr($receiver['payload']), true);
            if($pl === null) {
                continue;
            }

            if(!isset($pl['x'], $pl['y'], $pl['color'])) {
                continue;
            }

            $pl['x'] = (int)$pl['x'];
            $pl['y'] = (int)$pl['y'];
            if($pl['x'] < 1 && $pl['x'] > 10) {
                continue;
            }
            if($pl['y'] < 1 && $pl['y'] > 10) {
                continue;
            }
            $pl['color'] = substr($pl['color'], 1, 6);
            if (!ctype_xdigit($pl['color']) || strlen($pl['color']) < 6) {
                continue;
            }

            \Database\createOrUpdateOp(
                $pl['x'], $pl['y'], $pl['color'],
                $operation['senders'][$idx]['account'],
                $operation
            );
        }
    }
}