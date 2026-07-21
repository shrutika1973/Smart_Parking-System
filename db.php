<?php
$conn = new mysqli("sql303.infinityfree.com", "if0_42434955", "VAuCGC6MN08", "if0_42434955_parking_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>