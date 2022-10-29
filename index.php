<?php

require_once "core/init.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

?>

<!-- Your HTML code here -->