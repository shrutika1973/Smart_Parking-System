<?php
require_once 'db.php';

$result = $conn->query("SELECT * FROM parking ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Vehicles</title>

    <style>
        body{
            background:#1a0533;
            color:white;
            font-family:Arial;
            padding:30px;
        }

        h1{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#2d1b69;
        }

        th,td{
            padding:12px;
            border:1px solid rgba(255,255,255,0.2);
            text-align:center;
        }

        th{
            background:#4a2f8f;
        }

        tr:hover{
            background:#3a2478;
        }

        a{
            display:inline-block;
            margin-top:20px;
            padding:10px 20px;
            background:#00e676;
            color:white;
            text-decoration:none;
            border-radius:8px;
        }
    </style>
</head>
<body>

<h1>🚗 Parking Records</h1>

<table>
<tr>
    <th>ID</th>
    <th>Plate</th>
    <th>Type</th>
    <th>Spot</th>
    <th>Entry Time</th>
    <th>Exit Time</th>
    <th>Fee</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['plate'] ?></td>
    <td><?= $row['type'] ?></td>
    <td><?= $row['spot'] ?></td>
    <td><?= $row['entry_time'] ?></td>
    <td><?= $row['exit_time'] ?? '-' ?></td>
    <td>₹<?= $row['fee'] ?? 0 ?></td>
</tr>

<?php } ?>

</table>

<center>
<a href="index.php">🏠 Back to Dashboard</a>
</center>

</body>
</html>