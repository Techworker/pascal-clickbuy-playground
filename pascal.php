<?php

namespace Pascal;

function rpc(string $method, array $params = [])
{
    static $id = 0;

    $rpc = [
        'id' => $id++,
        'jsonrpc' => '2.0',
        'method' => $method,
        'params' => $params,
    ];
    if(!isset($rpc['params']['fee'])) {
        $rpc['params']['fee'] = '0.0000';
    } else {
        $rpc['params']['fee'] = '0.0001';
    }

    $ch = curl_init(NODE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rpc));

    $response = curl_exec($ch);
    \curl_close($ch);
    if ($response === false) {
        throw new \Exception('Unable to connect to node ' . NODE, 100);
    }

    $result = json_decode($response, true);
    if(isset($result['result'])) {
        return $result['result'];
    }

    if(isset($result['error']))
    {
        // if free didn't work out, try with fee
        if($rpc['params']['fee'] === '0.0000') {
            return rpc($method, $rpc['params']);
        }

        throw new \Exception($result['error']['message'], $result['error']['code']);
    }

    die('Invalid result: ' . print_r($result, true));
}

function getPendings() {

    $allPendings = [];
    $start = 0;
    $max = 100;
    do {
        $pendings = rpc('getpendings', [
            'max' => $max,
            'start' => 0
        ]);
        $allPendings = array_merge($allPendings, $pendings);
        $start += 100;
    } while(count($pendings) > 0 && count($pendings) === $max);

    return $allPendings;
}

function getBlockOps()
{
    $allPendings = [];
    $start = 0;
    $max = 100;
    do {
        $pendings = rpc('getpendings', [
            'max' => $max,
            'start' => 0
        ]);
        $allPendings = array_merge($allPendings, $pendings);
        $start += 100;
    } while(count($pendings) > 0 && count($pendings) === $max);

    return $allPendings;
}