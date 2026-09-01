<?php

session_start();

require_once "db.php";


// ==========================================
// CHECK REQUEST METHOD
// ==========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo "Invalid request.";

    exit;
}


// ==========================================
// GET FORM DATA
// ==========================================

$login = trim($_POST["login"] ?? "");

$password = $_POST["password"] ?? "";


// ==========================================
// VALIDATE INPUT
// ==========================================

if ($login === "" || $password === "") {

    echo "Email/mobile and password are required.";

    exit;
}


// ==========================================
// FIND USER
// ==========================================

$sql = "SELECT id, name, email, mobile, password
        FROM users
        WHERE email = ? OR mobile = ?
        LIMIT 1";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $login,
    $login
);

$stmt->execute();

$result = $stmt->get_result();


// ==========================================
// CHECK USER
// ==========================================

if ($result->num_rows === 0) {

    echo "Invalid email/mobile or password.";

    $stmt->close();
    $conn->close();

    exit;
}


$user = $result->fetch_assoc();


// ==========================================
// VERIFY PASSWORD
// ==========================================

if (!password_verify($password, $user["password"])) {

    echo "Invalid email/mobile or password.";

    $stmt->close();
    $conn->close();

    exit;
}


// ==========================================
// CREATE SESSION
// ==========================================

$_SESSION["user_id"] = $user["id"];

$_SESSION["user_name"] = $user["name"];

$_SESSION["user_email"] = $user["email"];

$_SESSION["logged_in"] = true;


// ==========================================
// LOGIN SUCCESS
// ==========================================

echo "Login successful!";


$stmt->close();

$conn->close();

?>