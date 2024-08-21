<?php
// index.php


session_start(); // Ensure session is started to check if user is logged in

// Determine the action to take based on URL parameters
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Include the layout which will use the $action variable
require_once "view/layout.php";
?>

