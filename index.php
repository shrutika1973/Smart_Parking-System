<!DOCTYPE html>
<html>
<head>
    <title>Smart Parking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #1a0533, #2d1b69, #1a0533);
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background stars */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120,40,200,0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(60,20,180,0.3) 0%, transparent 50%),
                radial-gradient(circle at 60% 80%, rgba(180,40,120,0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container { position: relative; z-index: 1; }

        h1.title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            padding: 30px 0 20px;
            text-shadow: 0 0 30px rgba(150,100,255,0.8);
            letter-spacing: 2px;
        }

        /* Cards */
        .glass-card {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .glass-card:hover { transform: translateY(-3px); }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        }

        /* Car images in cards */
        .card-img {
            position: absolute;
            right: -10px;
            bottom: -10px;
            width: 160px;
            opacity: 0.85;
            filter: drop-shadow(0 0 20px rgba(255,200,0,0.5));
        }

        .card-img-red {
            filter: drop-shadow(0 0 20px rgba(255,80,80,0.6));
        }

        .card-content { position: relative; z-index: 2; width: 65%; }

        /* Form inputs */
        .form-control {
            background: rgba(255,255,255,0.12) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: #fff !important;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .form-control::placeholder { color: rgba(255,255,255,0.5) !important; }
        .form-control:focus {
            box-shadow: 0 0 15px rgba(120,80,255,0.4) !important;
            border-color: rgba(150,100,255,0.6) !important;
        }

        select.form-control option { background: #2d1b69; color: #fff; }

        /* Buttons */
        .btn-park {
            background: linear-gradient(135deg, #00c853, #00e676);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            padding: 12px;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 5px 20px rgba(0,200,83,0.4);
            transition: all 0.3s;
        }
        .btn-park:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,200,83,0.6);
        }

        .btn-unpark {
            background: linear-gradient(135deg, #e53935, #ff5252);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            padding: 12px;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 5px 20px rgba(229,57,53,0.4);
            transition: all 0.3s;
        }
        .btn-unpark:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229,57,53,0.6);
        }

        .btn-view {
            background: linear-gradient(135deg, #3949ab, #5c6bc0);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            padding: 12px 30px;
            font-size: 1rem;
            box-shadow: 0 5px 20px rgba(57,73,171,0.4);
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-view:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 8px 25px rgba(57,73,171,0.6);
        }

        /* ✅ CHANGE 1 — New QR button style added */
        .btn-qr {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #00c853, #00e676);
            border: none;
            border-radius: 14px;
            color: #fff;
            font-weight: 700;
            padding: 14px 32px;
            font-size: 1rem;
            box-shadow: 0 5px 25px rgba(0,200,83,0.45);
            transition: all 0.3s;
            text-decoration: none;
            letter-spacing: 0.5px;
        }
        .btn-qr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,200,83,0.65);
            color: #fff;
        }
        .btn-qr svg { width: 22px; height: 22px; flex-shrink: 0; }

        /* Stats bar */
        .stats-bar {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-top: 15px;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }

        .stat-item { display: flex; align-items: center; gap: 6px; }
        .stat-num { font-weight: 800; font-size: 1.1rem; }
        .stat-green { color: #00e676; }
        .stat-yellow { color: #ffd740; }
        .stat-blue { color: #40c4ff; }
        .stat-divider { color: rgba(255,255,255,0.3); font-size: 1.2rem; }

        /* Status card */
        .status-card {
            background: linear-gradient(135deg, rgba(57,73,171,0.4), rgba(40,53,147,0.4));
            border: 1px solid rgba(100,120,255,0.3);
        }

        /* Chat */
        .chat-box {
            height: 220px;
            overflow-y: auto;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .chat-box::-webkit-scrollbar { width: 4px; }
        .chat-box::-webkit-scrollbar-track { background: transparent; }
        .chat-box::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        .user-msg {
            text-align: right;
            margin: 8px 0;
        }
        .user-msg span {
            background: linear-gradient(135deg, #5c35c8, #7c4dff);
            padding: 8px 14px;
            border-radius: 18px 18px 4px 18px;
            display: inline-block;
            font-size: 0.9rem;
            max-width: 80%;
        }

        .bot-msg {
            text-align: left;
            margin: 8px 0;
        }
        .bot-msg span {
            background: rgba(255,255,255,0.1);
            padding: 8px 14px;
            border-radius: 18px 18px 18px 4px;
            display: inline-block;
            font-size: 0.9rem;
            max-width: 80%;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .typing-msg span {
            background: rgba(255,255,255,0.07);
            padding: 8px 14px;
            border-radius: 18px;
            display: inline-block;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            font-style: italic;
        }

        .chat-input-row {
            display: flex;
            gap: 10px;
        }

        .btn-send {
            background: linear-gradient(135deg, #7c4dff, #651fff);
            border: none;
            border-radius: 12px;
            color: #fff;
            padding: 10px 20px;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(124,77,255,0.4);
            transition: all 0.3s;
            white-space: nowrap;
        }
        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(124,77,255,0.6);
        }

        /* Section headings */
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Glow effects */
        .glow-green { text-shadow: 0 0 10px rgba(0,230,118,0.8); }
        .glow-red   { text-shadow: 0 0 10px rgba(255,82,82,0.8); }

        /* Parking lot image placeholder */
        .parking-img {
            position: absolute;
            right: 15px;
            bottom: 10px;
            width: 180px;
            opacity: 0.7;
            filter: drop-shadow(0 0 15px rgba(100,150,255,0.5));
        }
    </style>
</head>

<body>

<?php
/* ✅ CHANGE 2 — Added PHP block to fetch live stats from DB.
   Previously the stats (150, 45, 105) were hardcoded fake numbers. */
require_once __DIR__ . '/db.php';
$total    = 50;
$res      = $conn->query("SELECT COUNT(*) AS cnt FROM parking WHERE exit_time IS NULL");
$occupied = $res ? (int)$res->fetch_assoc()['cnt'] : 0;
$free     = $total - $occupied;
$conn->close();
?>

<div class="container pb-5">

    <!-- Title -->
    <h1 class="title">🚗 Smart Parking System</h1>

    <!-- ✅ CHANGE 3 — Added "Scan QR to Park" button linking to scan.php.
         Was not present at all in the original file. -->
    <div class="text-center mb-4">
        <a href="scan.php" class="btn-qr">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 20h3"/>
            </svg>
            Scan QR to Park
        </a>
        <p style="margin-top:8px; font-size:0.8rem; opacity:0.5; letter-spacing:0.5px;">or use the form below</p>
    </div>

    <div class="row g-4">

        <!-- PARK VEHICLE -->
        <div class="col-md-6">
            <div class="glass-card" style="min-height:220px;">
                <div class="card-content">
                    <div class="section-title">
                        <i class="bi bi-car-front-fill"></i> Park Vehicle
                    </div>
                    <form action="park.php" method="POST">
                        <input type="text" name="plate" class="form-control mb-3"
                               placeholder="Enter Plate Number" required>

                        <!-- ✅ CHANGE 4 — Added value="" attributes to <option> tags.
                             Original had no value attributes so $_POST['type'] received
                             the display text. Now it sends CAR / BIKE / TRUCK correctly. -->
                        <select name="type" class="form-control mb-3">
                            <option value="CAR">Car</option>
                            <option value="BIKE">Bike</option>
                            <option value="TRUCK">SUV / Truck</option>
                        </select>

                        <button type="submit" class="btn-park">
                            ✅ Park Now
                        </button>
                    </form>
                </div>
                <!-- Yellow car SVG -->
                <svg class="card-img" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="110" rx="80" ry="8" fill="rgba(0,0,0,0.3)"/>
                    <rect x="20" y="60" width="160" height="45" rx="10" fill="#FFD600"/>
                    <rect x="40" y="35" width="110" height="35" rx="8" fill="#FFE033"/>
                    <rect x="45" y="38" width="48" height="25" rx="5" fill="#87CEEB" opacity="0.8"/>
                    <rect x="100" y="38" width="45" height="25" rx="5" fill="#87CEEB" opacity="0.8"/>
                    <circle cx="55" cy="105" r="16" fill="#333"/>
                    <circle cx="55" cy="105" r="9" fill="#666"/>
                    <circle cx="145" cy="105" r="16" fill="#333"/>
                    <circle cx="145" cy="105" r="9" fill="#666"/>
                    <rect x="155" y="68" width="25" height="15" rx="5" fill="#FFF176" opacity="0.9"/>
                    <rect x="20" y="68" width="20" height="12" rx="4" fill="#FF5252" opacity="0.9"/>
                </svg>
            </div>
        </div>

        <!-- UNPARK VEHICLE -->
        <div class="col-md-6">
            <div class="glass-card" style="min-height:220px;">
                <div class="card-content">
                    <div class="section-title">
                        <i class="bi bi-box-arrow-right"></i> Unpark Vehicle
                    </div>
                    <form action="unpark.php" method="POST">
                        <input type="text" name="plate" class="form-control mb-3"
                               placeholder="Enter Plate Number" required>
                        <button type="submit" class="btn-unpark">
                            ✖ Unpark
                        </button>
                    </form>

                    <!-- ✅ CHANGE 5 — Stats now use live PHP variables ($total, $free, $occupied)
                         instead of hardcoded fake values (150, 45, 105).
                         Also simplified to 3 clear stats: Total | Free | Occupied. -->
                    <div class="stats-bar">
                        <div class="stat-item">
                            <i class="bi bi-car-front"></i>
                            <span class="stat-num"><?= $total ?></span>
                            <span style="opacity:0.6">Spots</span>
                        </div>
                        <div class="stat-divider">|</div>
                        <div class="stat-item stat-green">
                            <i class="bi bi-check-circle"></i>
                            <span class="stat-num"><?= $free ?></span>
                            <span style="opacity:0.6">Free</span>
                        </div>
                        <div class="stat-divider">|</div>
                        <div class="stat-item stat-yellow">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span class="stat-num"><?= $occupied ?></span>
                            <span style="opacity:0.6">Occupied</span>
                        </div>
                    </div>
                </div>

                <!-- Red car SVG -->
                <svg class="card-img card-img-red" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="110" rx="80" ry="8" fill="rgba(0,0,0,0.3)"/>
                    <rect x="20" y="60" width="160" height="45" rx="10" fill="#E53935"/>
                    <rect x="40" y="35" width="110" height="35" rx="8" fill="#EF5350"/>
                    <rect x="45" y="38" width="48" height="25" rx="5" fill="#87CEEB" opacity="0.8"/>
                    <rect x="100" y="38" width="45" height="25" rx="5" fill="#87CEEB" opacity="0.8"/>
                    <circle cx="55" cy="105" r="16" fill="#333"/>
                    <circle cx="55" cy="105" r="9" fill="#666"/>
                    <circle cx="145" cy="105" r="16" fill="#333"/>
                    <circle cx="145" cy="105" r="9" fill="#666"/>
                    <rect x="155" y="68" width="25" height="15" rx="5" fill="#FFF176" opacity="0.9"/>
                    <rect x="20" y="68" width="20" height="12" rx="4" fill="#FF8A80" opacity="0.9"/>
                </svg>
            </div>
        </div>

    </div>

    <!-- PARKING STATUS -->
    <div class="glass-card status-card mt-4" style="position:relative; overflow:hidden;">
        <div style="position:relative; z-index:2;">
            <div class="section-title justify-content-center" style="font-size:1.3rem;">
                <i class="bi bi-bar-chart-fill"></i> Parking Status
            </div>
            <div class="text-center">
                <a href="view.php" class="btn-view">
                    <i class="bi bi-grid-3x3-gap-fill"></i> View All Vehicles
                </a>
            </div>
        </div>

        <!-- Parking lot SVG illustration -->
        <svg class="parking-img" viewBox="0 0 200 150" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="80" width="180" height="60" rx="5" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)"/>
            <line x1="70" y1="80" x2="70" y2="140" stroke="rgba(255,255,255,0.15)" stroke-width="1" stroke-dasharray="5,5"/>
            <line x1="130" y1="80" x2="130" y2="140" stroke="rgba(255,255,255,0.15)" stroke-width="1" stroke-dasharray="5,5"/>
            <rect x="20" y="90" width="40" height="25" rx="5" fill="#FFD600" opacity="0.7"/>
            <rect x="80" y="90" width="40" height="25" rx="5" fill="#E53935" opacity="0.7"/>
            <rect x="140" y="90" width="40" height="25" rx="5" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)"/>
            <rect x="85" y="20" width="6" height="60" fill="rgba(255,255,255,0.3)"/>
            <rect x="70" y="15" width="35" height="20" rx="3" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.2)"/>
            <text x="87" y="29" fill="rgba(255,255,255,0.6)" font-size="7" text-anchor="middle">P</text>
        </svg>
    </div>

    <!-- AI CHATBOT -->
    <div class="glass-card mt-4">
        <div class="section-title">
            <div style="width:32px;height:32px;background:linear-gradient(135deg,#7c4dff,#651fff);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                🤖
            </div>
            AI Assistant
        </div>

        <div id="chatBox" class="chat-box">
            <div class="bot-msg">
                <span>👋 Hello! I am your Smart Parking Assistant. Ask me anything!</span>
            </div>
        </div>

        <div class="chat-input-row">
            <input type="text" id="chatInput" class="form-control"
                   placeholder="Ask me anything...">
            <button class="btn-send" onclick="sendMessage()">
                <i class="bi bi-send-fill"></i> Send
            </button>
        </div>
    </div>

</div>

<script>
async function sendMessage() {
    let inputEl = document.getElementById("chatInput");
    let chatBox = document.getElementById("chatBox");
    let input   = inputEl.value.trim();

    if (input === "") return;

    chatBox.innerHTML += `<div class='user-msg'><span>👤 ${input}</span></div>`;
    inputEl.value = "";

    let typingId = "typing_" + Date.now();
    chatBox.innerHTML += `<div class='bot-msg typing-msg' id='${typingId}'><span>🤖 Typing...</span></div>`;
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const response = await fetch("chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: input })
        });

        const text = await response.text();
        const data = JSON.parse(text);

        document.getElementById(typingId).remove();
        chatBox.innerHTML += `<div class='bot-msg'><span>🤖 ${data.reply}</span></div>`;

    } catch (err) {
        document.getElementById(typingId).remove();
        chatBox.innerHTML += `<div class='bot-msg'><span style='color:#ff5252;'>❌ Error: ${err.message}</span></div>`;
    }

    chatBox.scrollTop = chatBox.scrollHeight;
}

document.getElementById("chatInput").addEventListener("keypress", function(e) {
    if (e.key === "Enter") sendMessage();
});
</script>

</body>
</html>