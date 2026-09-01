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

$mobile = trim($_POST["mobile"] ?? "");

$crop = trim($_POST["crop"] ?? "");

$category = trim($_POST["category"] ?? "");

$question = trim($_POST["question"] ?? "");


// ==========================================
// SERVER-SIDE VALIDATION
// ==========================================

if (
    $name === "" ||
    $mobile === "" ||
    $question === ""
) {

    echo "Please fill in all required fields.";

    exit;
}


// ==========================================
// VALIDATE NAME
// ==========================================

if (!preg_match("/^[A-Za-z ]+$/", $name)) {

    echo "Invalid farmer name.";

    exit;
}


// ==========================================
// VALIDATE MOBILE
// ==========================================

if (!preg_match("/^[0-9]{10}$/", $mobile)) {

    echo "Invalid mobile number.";

    exit;
}


// ==========================================
// VALIDATE QUESTION
// ==========================================

if (strlen($question) < 10) {

    echo "Please provide more details about your question.";

    exit;
}


// ==========================================
// INSERT INTO DATABASE
// ==========================================

$sql = "INSERT INTO farmer_queries
        (name, mobile, crop, category, question)
        VALUES (?, ?, ?, ?, ?)";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    echo "Database error.";

    exit;
}


$stmt->bind_param(
    "sssss",
    $name,
    $mobile,
    $crop,
    $category,
    $question
);


// ==========================================
// EXECUTE QUERY
// ==========================================

if ($stmt->execute()) {

    echo "Question submitted successfully!";

} else {

    echo "Failed to submit question.";

}


// ==========================================
// CLOSE CONNECTION
// ==========================================

$stmt->close();

$conn->close();

?>