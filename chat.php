<?php
header("Content-Type: application/json");

$data    = json_decode(file_get_contents("php://input"), true);
$message = strtolower(trim($data['message'] ?? ''));

if (empty($message)) {
    echo json_encode(["reply" => "Empty message received"]);
    exit();
}

// ✅ CHANGE 1 — DB connection added so chatbot can give live spot counts.
//    Original had no DB access at all; spot replies were always a generic static string.
require_once __DIR__ . '/db.php';

$reply = "";

// Price / Fee
// ✅ CHANGE 2 — Added "rate", "minimum", "rupee", "rs" as extra keywords.
if (strpos($message, "price")   !== false || strpos($message, "fee")     !== false ||
    strpos($message, "cost")    !== false || strpos($message, "charge")   !== false ||
    strpos($message, "rate")    !== false || strpos($message, "minimum")  !== false ||
    strpos($message, "rupee")   !== false || strpos($message, "rs")       !== false) {
    $reply = "Parking fee is ₹5 per hour. Minimum 1 hour is charged. Fee is calculated from entry to exit time and rounded up.";
}

// Hello / Greeting
elseif (strpos($message, "hello") !== false || strpos($message, "hi")  !== false ||
        strpos($message, "hey")   !== false || strpos($message, "good") !== false ||
        strpos($message, "helo")  !== false) {
    $reply = "Hello! Welcome to Smart Parking System. How can I help you today?";
}

// Spot / Availability — ✅ CHANGE 3 — Now queries DB for real free/occupied count.
//    Original always replied with a fixed string regardless of actual availability.
elseif (strpos($message, "spot")      !== false || strpos($message, "available") !== false ||
        strpos($message, "space")     !== false || strpos($message, "capacity")  !== false ||
        strpos($message, "free")      !== false || strpos($message, "empty")     !== false ||
        strpos($message, "how many")  !== false || strpos($message, "full")      !== false) {
    $total    = 50;
    $res      = $conn->query("SELECT COUNT(*) AS cnt FROM parking WHERE exit_time IS NULL");
    $occupied = $res ? (int)$res->fetch_assoc()['cnt'] : 0;
    $free     = $total - $occupied;
    if ($free === 0) {
        $reply = "Sorry, the parking lot is currently FULL ($total/$total spots occupied). Please check back soon!";
    } else {
        $reply = "Currently $free out of $total spots are free ($occupied occupied). Spots are auto-assigned when you park.";
    }
}

// Zone / Map / QR / Scan — ✅ CHANGE 4 — New topic block, did not exist in original.
elseif (strpos($message, "zone")   !== false || strpos($message, "map")    !== false ||
        strpos($message, "qr")     !== false || strpos($message, "scan")   !== false ||
        strpos($message, "locate") !== false || strpos($message, "find")   !== false ||
        strpos($message, "where")  !== false || strpos($message, "location") !== false) {
    $reply = "The lot has 4 zones — A (nearest, spots 1–10), B (spots 11–20), C (spots 21–35), D (far end, spots 36–50). After parking, your spot is shown on the live map. You can also scan the QR code at the entrance to start parking.";
}

// Receipt / History / Record — ✅ CHANGE 5 — New topic block, did not exist in original.
elseif (strpos($message, "receipt")  !== false || strpos($message, "history")  !== false ||
        strpos($message, "record")   !== false || strpos($message, "log")       !== false ||
        strpos($message, "bill")     !== false || strpos($message, "invoice")   !== false) {
    $reply = "You can view all parking records including entry time, exit time, and fee on the 'View All Vehicles' page from the dashboard.";
}

// Vehicle type
// ✅ CHANGE 6 — Added "suv", "scooter", "bicycle", "two", "four" as extra keywords.
elseif (strpos($message, "vehicle") !== false || strpos($message, "type")    !== false ||
        strpos($message, "car")     !== false || strpos($message, "bike")    !== false ||
        strpos($message, "truck")   !== false || strpos($message, "suv")     !== false ||
        strpos($message, "scooter") !== false || strpos($message, "two")     !== false ||
        strpos($message, "four")    !== false) {
    $reply = "We support 3 vehicle types: CAR, BIKE, and SUV/TRUCK. Select your type when entering your plate number.";
}

// ✅ CHANGE 7 — "unpark" block moved ABOVE "park" block.
//    Original bug: "unpark" contains the word "park", so the park block always fired first,
//    giving the wrong reply for any unpark-related question.
// Unpark / Exit / Leave
elseif (strpos($message, "unpark") !== false || strpos($message, "exit")    !== false ||
        strpos($message, "leave")  !== false || strpos($message, "out")     !== false ||
        strpos($message, "remove") !== false || strpos($message, "checkout") !== false) {
    $reply = "To unpark: enter your plate number in the Unpark section on the dashboard and click Unpark. Your fee will be shown instantly.";
}

// Park
elseif (strpos($message, "park") !== false) {
    $reply = "To park: enter your plate number, select your vehicle type, then click Park Now. You will be redirected to the map showing your allocated spot.";
}

// Time / Hours
elseif (strpos($message, "time") !== false || strpos($message, "hour")    !== false ||
        strpos($message, "duration") !== false || strpos($message, "long") !== false) {
    $reply = "Fee is calculated from your entry time to exit time. ₹5 per hour, rounded up to the next hour. Minimum charge is 1 hour (₹5).";
}

// Help
elseif (strpos($message, "help") !== false || strpos($message, "support") !== false ||
        strpos($message, "assist") !== false || strpos($message, "what can") !== false) {
    $reply = "I can help with: 🅿️ Parking fee & rates | 🚗 Vehicle types | 📍 Spot availability & zones | ⬆️ How to park | ⬇️ How to unpark | 🧾 Records & receipts | 🗺️ Map & QR scan.";
}

// Thank you
elseif (strpos($message, "thank") !== false || strpos($message, "thanks") !== false ||
        strpos($message, "thx")   !== false || strpos($message, "ty")     !== false) {
    $reply = "You are welcome! Happy parking! 🚗";
}

// How
elseif (strpos($message, "how") !== false) {
    $reply = "To park: enter your plate number → select vehicle type → click Park Now. You will be shown your allocated spot on the map!";
}

// ✅ CHANGE 8 — Improved default reply to list specific topics users can ask about.
else {
    $reply = "I can help with parking fee, spot availability, vehicle types, zones, how to park or unpark, and viewing records. Try asking something like 'How many spots are free?' or 'What is the parking fee?'";
}

$conn->close();
echo json_encode(["reply" => $reply]);
?>