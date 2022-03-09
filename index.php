<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    echo "Hello World";
} catch(Exception $e) {
    echo $e->getMessage();
}
?>
