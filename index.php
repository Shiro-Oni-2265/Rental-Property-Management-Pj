<?php
session_start();
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Router.php';

// Instantiate DB connection
$db = new Database();
$conn = $db->getConnection();

// Instantiate Router and dispatch
$router = new Router();
$router->handleRequest($conn);
?>
