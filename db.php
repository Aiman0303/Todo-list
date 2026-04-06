<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "todo";

$conn = new mysqli($host,$user,$pass,$dbname);

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "database connection failed"
    ]));
}

header("Content-Type: application/json");
?>