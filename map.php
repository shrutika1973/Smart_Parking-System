<?php

$spot  = $_GET['spot'] ?? 1;
$plate = $_GET['plate'] ?? '';

?>

<!DOCTYPE html>
<html>
<head>
<title>Parking Map</title>

<style>

body{
    background:#1a0533;
    color:white;
    font-family:Arial;
    text-align:center;
}

h1{
    margin-top:20px;
}

.map{
    width:900px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(10,1fr);
    gap:15px;
    margin-top:40px;
}

.slot{
    height:70px;
    background:#444;
    border-radius:10px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
}

.active{
    background:#00e676;
    color:black;
    animation:blink 1s infinite;
}

@keyframes blink{
    0%{transform:scale(1);}
    50%{transform:scale(1.1);}
    100%{transform:scale(1);}
}

.btn{
    display:inline-block;
    margin-top:30px;
    padding:12px 20px;
    background:#00e676;
    color:white;
    text-decoration:none;
    border-radius:10px;
}

</style>

</head>
<body>

<h1>🅿️ Parking Spot Allocated</h1>

<h2>
Vehicle :
<?= htmlspecialchars($plate) ?>
</h2>

<h3>
Allocated Spot :
<?= $spot ?>
</h3>

<div class="map">

<?php
for($i=1;$i<=50;$i++)
{
    $class = ($i==$spot) ? 'slot active' : 'slot';

    echo "<div class='$class'>$i</div>";
}
?>

</div>

<a href="index.php" class="btn">
Back To Dashboard
</a>

</body>
</html>