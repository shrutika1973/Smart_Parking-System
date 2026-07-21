<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code</title>

    <style>
        body{
            font-family:Arial,sans-serif;
            background:#1a0533;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            margin:0;
        }

        .box{
            background:#2d1b69;
            padding:30px;
            border-radius:15px;
            text-align:center;
            width:400px;
        }

        img{
            width:250px;
            margin:20px 0;
        }

        a{
            display:inline-block;
            padding:10px 20px;
            background:#00e676;
            color:white;
            text-decoration:none;
            border-radius:8px;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>Scan QR Code</h2>

   <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=http://10.107.255.186/parking-system/index.php"
    alt="QR Code">
    <p>Scan this QR to start parking.</p>

    <a href="index.php">Back to Dashboard</a>

</div>

</body>
</html>