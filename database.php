<?php

namespace Database;

\ORM::configure('sqlite:' . __DIR__ . '/data.db');
\ORM::configure('logging', true);
$db = \ORM::getDb();

$db->exec('
CREATE TABLE IF NOT EXISTS grid (
    id INTEGER PRIMARY KEY, 
    x INTEGER,
    y INTEGER,
    color VARCHAR(6),
    sender INTEGER,
    ophash VARCHAR(255),
    iteration INTEGER,
    price INTEGER
)');

$db->exec('
CREATE TABLE IF NOT EXISTS evt (
    id INTEGER PRIMARY KEY, 
    ts INTEGER,
    msg text
)');

$db->exec('CREATE TABLE IF NOT EXISTS ops (
    id INTEGER PRIMARY KEY,
    grid_id INTEGER,
    block INTEGER,
    opblock INTEGER,
    ophash VARCHAR(255),
    sender INTEGER,
    color VARCHAR(6),
    x INTEGER,
    y INTEGER,
    pending INTEGER,
    ts INTEGER
)');

function addEvent($msg) {
    $evt = \ORM::forTable('evt')->create();
    $evt->ts = time();
    $evt->msg = $msg;
    $evt->save();
}

function addGrid(int $x, int $y) {
    $grid = \ORM::forTable('grid')->create();
    $grid->x = $x;
    $grid->y = $y;
    $grid->color = 'FFFFFF';
    $grid->sender = 0;
    $grid->ophash = '';
    $grid->iteration = 1;
    $grid->price = 100;
    $grid->save();
}

function getGrid(int $x, int $y) {
    return \ORM::forTable('grid')
        ->where('x', $x)
        ->where('y', $y)
        ->findOne();
}

function getCompleteGrid() {
    return \ORM::forTable('grid')
        ->findMany();
}

function getLatestOp($gridId) {
    return \ORM::forTable('ops')
        ->where('grid_id', $gridId)
        ->orderByDesc('ts')
        ->orderByDesc('pending')
        ->orderByDesc('block')
        ->orderByDesc('opblock')
        ->limit(1)
        ->findOne();
}

function getLastEvents() {
    return \ORM::forTable('evt')
        ->orderByDesc('ts')
        ->limit(5)
        ->findMany();
}


function createOrUpdateOp(int $x, int $y, string $color, $sender, array $operation)
{
    $op = getByOpHash($operation['ophash']);
    if($op === false) {
        $grid = getGrid($x, $y);
        $op = \ORM::forTable('ops')->create();
        $op->grid_id = $grid->id;
        $op->ophash = ophash($operation['ophash']);
        $op->color = $color;
        $op->sender = $sender;
        $op->x = $x;
        $op->y = $y;
        $op->ts = time();
    }
    $op->pending = (int)($operation['block'] == 0);
    $op->block = $operation['block'];
    $op->opblock = $operation['block'];
    $op->save();

    return $op;
}

function getByOpHash($opHash) {
    return \ORM::forTable('ops')
        ->where('ophash', ophash($opHash))
        ->findOne();
}


function ophash($opHash) {
    return substr($opHash, 8);
}