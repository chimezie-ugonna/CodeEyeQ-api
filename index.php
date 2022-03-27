<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/api/config/database.php";
    echo "index.php file.";
} catch (Exception $e) {
    echo $e->getMessage();
}
