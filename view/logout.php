<?php
include_once 'includes/functions.inc.php';

session_start();
session_destroy();
redirect('index.php?action=home');
?>
