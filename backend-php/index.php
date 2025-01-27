<?php
header('Content-Type: application/json; charset=utf-8');

require 'router.php';

$router = new Router();
$router->route();
?>