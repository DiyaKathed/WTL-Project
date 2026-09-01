<?php

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

$name = trim($_POST["name"] ?? "");

$email = trim($_POST["email"] ?? "");

$message = trim($_POST["message"] ?? "");


// ==========================================
// SERVER-SIDE VALIDATION
// ==========================================

if (
    $name === "" ||
    $email === "" ||
    $message === ""
) {

    echo "All fields are required.";

    exit;
}


// ==========================================
// VALIDATE NAME
// ==========================================

if (!preg_match("/^[A-Za-z ]+$/", $name)) {

    echo "Invalid name.";

    exit;
}


// ==========================================
// VALIDATE EMAIL
// ==========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "Invalid email address.";

    exit;
}


// ==========================================
// VALIDATE MESSAGE
// ==========================================

if (strlen($message) < 10) {

    echo "Please provide more details in your message.";

    exit;
}


// ==========================================
// INSERT INTO DATABASE
// ==========================================

$sql = "INSERT INTO contact_messages
        (name, email, message)
        VALUES (?, ?, ?)";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    echo "Database error.";

    exit;
}


$stmt->bind_param(
    "sss",
    $name,
    $email,
    $message
);


// ==========================================
// EXECUTE QUERY
// ==========================================

if ($stmt->execute()) {

    echo "Message sent successfully!";

} else {

    echo "Failed to send message.";

}


// ==========================================
// CLOSE CONNECTION
// ==========================================

$stmt->close();

$conn->close();

?>