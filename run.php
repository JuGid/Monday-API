<?php

require __DIR__.'/vendor/autoload.php';

use Manday\Manday;

$api = new Manday();

$query_ql = $api->createGraphQL()
                ->request('items', ['limit'=>1])
                ->the('column_values', ['title', 'type'])
                ->the('owner', ['id']);
$query_ql_info = $api->createGraphQL()
                     ->request('items', ['limit'=>1])
                     ->the('column_values', ['title', 'type', 'owner'=>['id']]);
$modif_ql = $api->createGraphQL()
                ->modify()
                ->the('create_board', ['id'], ['board_name'=>'The board', 'board_kind'=>'public']);

echo $query_ql->getQuery(), "\n";
echo $query_ql_info->getQuery(), "\n";
echo $modif_ql->getQuery(), "\n";

/*
try {
    $api->send($query_ql, true);
} catch(\Exception $e) {
    echo $e->getMessage(), "\n";
}

var_dump($api->getResult());
*/


