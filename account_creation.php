<?php

try{
    //Set DSN data source name
    $dsn = "pgsql:host=ec2-44-194-54-186.compute-1.amazonaws.com;
    port=5432;
    dbname=dco8bgoaljvb7;
    user=uaqteoyzlwegvo;
    password=b210289ab147c6c6c2ff023fc8b91a61cd29708140f2b06e9c9dbaf06da94e42;
    sslmode=require;";


    //create a pdo instance
    $pdo = new PDO($dsn, "uaqteoyzlwegvo", "b210289ab147c6c6c2ff023fc8b91a61cd29708140f2b06e9c9dbaf06da94e42");
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connection success';
}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
