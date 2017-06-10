<?php
// Rotas
$app->get('/', 'IndexController@index');
$app->get('/{outputType}/{objects}', 'IndexController@discoverObjects');