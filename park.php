<?php
// ✅ CHANGE 1 — Turned off error display for production.
//    Original had error_reporting(E_ALL) + display_errors=1 which
//    exposes server internals to users. Errors now go to server log only.
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
require_once 'db.php';

// Block direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

if ($conn->connect_error) {
    die("❌ DB Error: " . $conn->connect_error);
}

$plate = strtoupper(trim($_POST['plate'] ?? ''));
$type  = trim($_POST['type'] ?? '');

if ($plate === '' || $type === '') {
    die("❌ Please fill all fields");
}

// ✅ CHANGE 2 — Added plate format validation.
//    Original accepted any string including symbols, numbers-only, or 1-char input.
//    Now requires at least 4 characters and only allows letters, digits, and spaces/hyphens.
if (strlen($plate) < 4 || !preg_match('/^[A-Z0-9 \-]+$/', $plate)) {
    echo "<script>alert('❌ Invalid plate number format. Use letters and numbers only (e.g. MH 12 AB 1234).'); window.location.href='scan.php';</script>";
    exit();
}

// ✅ CHANGE 3 — Added allowed vehicle type whitelist.
//    Original did no type validation so anyone could POST any string as the type.
$allowedTypes = ['CAR', 'BIKE', 'TRUCK'];
if (!in_array($type, $allowedTypes)) {
    echo "<script>alert('❌ Invalid vehicle type selected.'); window.location.href='scan.php';</script>";
    exit();
}

// Fraud detection
// ✅ CHANGE 4 — Fraud detection now BLOCKS parking instead of just alerting.
//    Original showed an alert but still allowed the vehicle to park.
if (!isset($_SESSION['entryCount'])) {
    $_SESSION['entryCount'] = [];
}
$_SESSION['entryCount'][$plate] = ($_SESSION['entryCount'][$plate] ?? 0) + 1;
if ($_SESSION['entryCount'][$plate] > 5) {
    echo "<script>alert('⚠️ Suspicious activity detected. Entry blocked. Please contact staff.'); window.location.href='index.php';</script>";
    exit();
}

// Check duplicate
$check = $conn->prepare("SELECT id FROM parking WHERE plate = ? AND exit_time IS NULL");
if (!$check) {
    die("❌ Prepare Error: " . $conn->error);
}
$check->bind_param("s", $plate);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $check->close();
    // ✅ CHANGE 5 — Duplicate vehicle now redirects to scan.php instead of index.php
    //    so the user stays on the entry flow, not the dashboard.
    echo "<script>alert('❌ Vehicle already parked! Unpark it first before re-entering.'); window.location.href='scan.php';</script>";
    exit();
}
$check->close();

// ✅ CHANGE 6 — Added lot full check before assigning a spot.
//    Original never checked capacity — spot numbers would exceed the lot size (50).
$total      = 50;
$capResult  = $conn->query("SELECT COUNT(*) AS cnt FROM parking WHERE exit_time IS NULL");
$capRow     = $capResult->fetch_assoc();
$currentCnt = (int)($capRow['cnt'] ?? 0);

if ($currentCnt >= $total) {
    echo "<script>alert('❌ Parking lot is FULL! No spots available right now.'); window.location.href='index.php';</script>";
    exit();
}

// Assign spot
// ✅ CHANGE 7 — Spot assignment now finds the LOWEST available spot number
//    instead of COUNT(*)+1 which breaks when vehicles unpark (leaves gaps).
//    e.g. if spot 3 is freed, COUNT(*)+1 might give spot 8 again instead of reusing 3.
$usedSpots = [];
$spotRes   = $conn->query("SELECT spot FROM parking WHERE exit_time IS NULL ORDER BY spot ASC");
while ($r = $spotRes->fetch_assoc()) {
    $usedSpots[] = (int)$r['spot'];
}
$spot = 1;
while (in_array($spot, $usedSpots)) {
    $spot++;
}

// Insert
$stmt = $conn->prepare("INSERT INTO parking (plate, type, spot, entry_time) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    die("❌ Prepare Failed: " . $conn->error);
}
$stmt->bind_param("ssi", $plate, $type, $spot);

if ($stmt->execute()) {
    // ✅ CHANGE 8 — Success now redirects to map.php with plate & spot in URL
    //    instead of showing an alert() and going back to index.php.
    //    User now sees the live parking map with their allocated spot highlighted.
    $stmt->close();
    $conn->close();
    header("Location: map.php?plate=" . urlencode($plate) . "&spot=" . $spot);
    exit();
} else {
    echo "❌ Insert Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>