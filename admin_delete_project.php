<?php
session_start();

// Only logged-in administrators can delete projects.
if (!isset($_SESSION["verona_admin"])) {
    header("Location: admin_login.php");
    exit;
}

require_once "db.php";

// Deletion must use POST, not a direct URL.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_manage_projects.php");
    exit;
}

// Validate the CSRF token.
$submitted_token = $_POST["csrf_token"] ?? "";
$session_token = $_SESSION["verona_csrf"] ?? "";

if (
    !is_string($submitted_token) ||
    !is_string($session_token) ||
    $session_token === "" ||
    !hash_equals($session_token, $submitted_token)
) {
    http_response_code(403);
    exit("Security check failed. Return to Manage Projects and try again.");
}

// Validate the project ID.
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: admin_manage_projects.php");
    exit;
}

// Delete using a prepared statement.
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $stmt->close();
    header("Location: admin_manage_projects.php?deleted=1");
    exit;
}

$stmt->close();
header("Location: admin_manage_projects.php?deleted=0");
exit;
?>