<?php
// Block direct URL access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// ✅ CHANGE 1 — Changed include to require_once.
//    Original used include 'db.php' which silently continues if file is missing.
//    require_once stops execution immediately if db.php cannot be loaded.
require_once 'db.php';

// ✅ CHANGE 2 — Set timezone explicitly so PHP time() matches MySQL NOW().
//    Original had no timezone set — if server PHP timezone differed from MySQL timezone,
//    fee calculation would be wrong (e.g. 1 hour session billed as 6 hours).
date_default_timezone_set('Asia/Kolkata');

$plate = strtoupper(trim($_POST['plate'] ?? ''));

if ($plate === '') {
    echo "<script>alert('❌ Please enter a plate number.'); window.location.href='index.php';</script>";
    exit();
}

// ✅ CHANGE 3 — Added plate format validation.
//    Original accepted any string. Now rejects plates shorter than 4 chars
//    or containing special characters.
if (strlen($plate) < 4 || !preg_match('/^[A-Z0-9 \-]+$/', $plate)) {
    echo "<script>alert('❌ Invalid plate number format.'); window.location.href='index.php';</script>";
    exit();
}

// Use prepared statement (safe)
$stmt = $conn->prepare("SELECT * FROM parking WHERE plate = ? AND exit_time IS NULL");
$stmt->bind_param("s", $plate);
$stmt->execute();
$result = $stmt->get_result();
$row    = $result->fetch_assoc();

if ($row) {
    $entry = strtotime($row['entry_time']);
    $exit  = time();

    // ✅ CHANGE 4 — Minimum 1 hour enforced in seconds before ceil().
    //    Original: if entry and exit were within the same minute, ceil() gave 0 hours → ₹0 fee.
    //    Now: minimum duration is 3600 seconds (1 hour) before any calculation.
    $duration = max($exit - $entry, 3600);
    $hours    = ceil($duration / 3600);
    $fee      = $hours * 5;

    // ✅ CHANGE 5 — Added error check on the UPDATE statement.
    //    Original called $update->execute() with no check — if it failed silently,
    //    the vehicle would never be marked as exited in the DB but user saw "success".
    $update = $conn->prepare("UPDATE parking SET exit_time = NOW(), fee = ? WHERE id = ?");
    $update->bind_param("ii", $fee, $row['id']);

    if ($update->execute()) {
        $update->close();
        $stmt->close();
        $conn->close();

        // ✅ CHANGE 6 — Redirect to a receipt page instead of an alert() popup.
        //    Original used alert() which is ugly, can't be styled, and loses data on dismiss.
        //    Now passes plate, fee, hours, spot via URL to receipt.php for a proper receipt UI.
        header("Location: receipt.php?plate=" . urlencode($plate)
            . "&fee="   . $fee
            . "&hours=" . $hours
            . "&spot="  . urlencode($row['spot'])
            . "&entry=" . urlencode($row['entry_time']));
        exit();

    } else {
        // ✅ CHANGE 5 (continued) — Update failure now shown to user instead of silent fail.
        $update->close();
        echo "<script>alert('❌ Failed to process unpark. Please try again or contact staff.'); window.location.href='index.php';</script>";
    }

} else {
    // ✅ CHANGE 7 — Not-found message is more helpful.
    //    Original: "Vehicle not found or already unparked!" — user didn't know what went wrong.
    //    Now tells them to double-check the plate or visit View All Vehicles.
    echo "<script>alert('❌ No active parking found for plate: $plate\nPlease check the plate number or visit View All Vehicles.'); window.location.href='index.php';</script>";
}

$stmt->close();
$conn->close();
?>