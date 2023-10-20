<?php

$conn = new mysqli('localhost', 'root', '', 'db_company');

if ($conn->connect_error) {
    die('Connection failed' . $conn->connect_error);
}
// echo 'connected';
