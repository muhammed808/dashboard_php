<?php

$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = '';

try{
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo "failed to conected " . $e->getMessage();
}
?>