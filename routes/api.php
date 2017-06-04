<?php


$app->get('/', 'IndexController@index');
$app->get('/{outputType}/{objects}', 'IndexController@discoverObjects');
