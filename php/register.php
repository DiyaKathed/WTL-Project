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

$mobile = trim($_POST["mobile"] ?? "");

$password = $_POST["password"] ?? "";


// ==========================================
// SERVER-SIDE VALIDATION
// ==========================================

if (
    $name === "" ||
    $email === "" ||
    $mobile === "" ||
    $password === ""
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
// VALIDATE MOBILE
// ==========================================

if (!preg_match("/^[0-9]{10}$/", $mobile)) {

    echo "Invalid mobile number.";

    exit;
}


// ==========================================
// VALIDATE PASSWORD
// ==========================================

if (strlen($password) < 6) {

    echo "Password must contain at least 6 characters.";

    exit;
}


// ==========================================
// CHECK EMAIL
// ==========================================

$checkSql =
    "SELECT id FROM users WHERE email = ?";

$checkStmt =
    $conn->prepare($checkSql);

if (!$checkStmt) {

    echo "Database error.";

    exit;
}

$checkStmt->bind_param(
    "s",
    $email
);

$checkStmt->execute();

$checkStmt->store_result();


if ($checkStmt->num_rows > 0) {

    echo "An account with this email already exists.";

    $checkStmt->close();

    $conn->close();

    exit;
}

$checkStmt->close();


// ==========================================
// CHECK FARMER PHOTO
// ==========================================

if (!isset($_FILES["photo"]) ||
    $_FILES["photo"]["error"] !== UPLOAD_ERR_OK) {

    echo "Please upload your farmer photo.";

    exit;
}


// ==========================================
// CHECK ID PROOF
// ==========================================

if (!isset($_FILES["idProof"]) ||
    $_FILES["idProof"]["error"] !== UPLOAD_ERR_OK) {

    echo "Please upload your ID proof.";

    exit;
}


// ==========================================
// FILE SIZE LIMIT
// Maximum 2 MB each
// ==========================================

$maxFileSize = 2 * 1024 * 1024;

if ($_FILES["photo"]["size"] > $maxFileSize) {

    echo "Farmer photo must be less than 2 MB.";

    exit;
}

if ($_FILES["idProof"]["size"] > $maxFileSize) {

    echo "ID proof must be less than 2 MB.";

    exit;
}


// ==========================================
// ALLOWED FILE TYPES
// ==========================================

$allowedPhotoTypes = [
    "image/jpeg",
    "image/png"
];

$allowedIdTypes = [
    "image/jpeg",
    "image/png",
    "application/pdf"
];


// ==========================================
// CHECK PHOTO TYPE
// ==========================================

$photoMime =
    mime_content_type($_FILES["photo"]["tmp_name"]);

if (!in_array($photoMime, $allowedPhotoTypes)) {

    echo "Invalid farmer photo format. Use JPG or PNG.";

    exit;
}


// ==========================================
// CHECK ID PROOF TYPE
// ==========================================

$idMime =
    mime_content_type($_FILES["idProof"]["tmp_name"]);

if (!in_array($idMime, $allowedIdTypes)) {

    echo "Invalid ID proof format. Use JPG, PNG or PDF.";

    exit;
}


// ==========================================
// CREATE UPLOAD DIRECTORIES
// ==========================================

$photoDirectory =
    "../uploads/farmer_photos/";

$idDirectory =
    "../uploads/id_proofs/";


if (!is_dir($photoDirectory)) {

    mkdir($photoDirectory, 0777, true);
}

if (!is_dir($idDirectory)) {

    mkdir($idDirectory, 0777, true);
}


// ==========================================
// GENERATE UNIQUE FILE NAMES
// ==========================================

$photoExtension =
    strtolower(
        pathinfo(
            $_FILES["photo"]["name"],
            PATHINFO_EXTENSION
        )
    );

$idExtension =
    strtolower(
        pathinfo(
            $_FILES["idProof"]["name"],
            PATHINFO_EXTENSION
        )
    );


$uniqueName =
    uniqid("farmer_", true);

$photoFileName =
    $uniqueName . "." . $photoExtension;

$idFileName =
    $uniqueName . "_id." . $idExtension;


// ==========================================
// COMPLETE FILE PATHS
// ==========================================

$photoPath =
    $photoDirectory . $photoFileName;

$idPath =
    $idDirectory . $idFileName;


// ==========================================
// MOVE UPLOADED FILES
// ==========================================

if (!move_uploaded_file(
    $_FILES["photo"]["tmp_name"],
    $photoPath
)) {

    echo "Failed to upload farmer photo.";

    exit;
}


if (!move_uploaded_file(
    $_FILES["idProof"]["tmp_name"],
    $idPath
)) {

    // Delete already uploaded photo
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    echo "Failed to upload ID proof.";

    exit;
}


// ==========================================
// DATABASE PATHS
// ==========================================

$dbPhotoPath =
    "uploads/farmer_photos/" . $photoFileName;

$dbIdPath =
    "uploads/id_proofs/" . $idFileName;


// ==========================================
// HASH PASSWORD
// ==========================================

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


// ==========================================
// INSERT USER
// ==========================================

$sql =
    "INSERT INTO users
    (name, email, mobile, password, photo, id_proof)
    VALUES (?, ?, ?, ?, ?, ?)";


$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    // Remove uploaded files if database preparation fails
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    if (file_exists($idPath)) {
        unlink($idPath);
    }

    echo "Database error.";

    exit;
}


$stmt->bind_param(
    "ssssss",
    $name,
    $email,
    $mobile,
    $hashedPassword,
    $dbPhotoPath,
    $dbIdPath
);


// ==========================================
// EXECUTE
// ==========================================

if ($stmt->execute()) {

    echo "Registration successful!";

} else {

    // Remove uploaded files if registration fails

    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    if (file_exists($idPath)) {
        unlink($idPath);
    }

    echo "Registration failed.";
}


$stmt->close();

$conn->close();

?>