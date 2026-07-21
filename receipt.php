<?php

$plate = strtoupper($_GET['plate'] ?? 'UNKNOWN');
$fee   = $_GET['fee'] ?? 0;
$hours = $_GET['hours'] ?? 1;
$spot  = $_GET['spot'] ?? '-';
$entry = $_GET['entry'] ?? '-';
$exit  = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html>
<head>
<title>Parking Receipt</title>

<style>

body{
    background:#1a0533;
    color:white;
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.receipt{
    width:450px;
    background:#2d1b69;
    padding:30px;
    border-radius:20px;
}

h1{
    text-align:center;
    color:#00e676;
}

.row{
    display:flex;
    justify-content:space-between;
    margin:10px 0;
}

.total{
    font-size:30px;
    color:#00e676;
    text-align:center;
    margin-top:20px;
}

.btn{
    display:block;
    text-align:center;
    padding:12px;
    margin-top:20px;
    background:#00e676;
    color:white;
    text-decoration:none;
    border-radius:10px;
}

</style>

</head>
<body>

<div class="receipt">

<h1>✅ Parking Receipt</h1>

<div class="row">
<span>Plate Number</span>
<span><?= $plate ?></span>
</div>

<div class="row">
<span>Spot</span>
<span><?= $spot ?></span>
</div>

<div class="row">
<span>Entry Time</span>
<span><?= $entry ?></span>
</div>

<div class="row">
<span>Exit Time</span>
<span><?= $exit ?></span>
</div>

<div class="row">
<span>Duration</span>
<span><?= $hours ?> Hours</span>
</div>

<div class="row">
<span>Rate</span>
<span>₹5 / Hour</span>
</div>

<hr>

<div class="total">
₹<?= $fee ?>
</div>

<a href="index.php" class="btn">Back To Dashboard</a>

</div>

</body>
</html>