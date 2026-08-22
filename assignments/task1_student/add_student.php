<?php
header('Content-Type: application/json');
require_once 'db.php';

function respond($success, $message, $errors = []) {
    echo json_encode(["success" => $success, "message" => $message, "errors" => $errors]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, "Invalid request method.");
}

$name          = trim($_POST['name'] ?? '');
$gender        = trim($_POST['gender'] ?? '');
$standard      = trim($_POST['standard'] ?? '');
$dob           = trim($_POST['dob'] ?? '');
$father_name   = trim($_POST['father_name'] ?? '');
$father_mobile = trim($_POST['father_mobile'] ?? '');
$email         = trim($_POST['email'] ?? '');

$errors = [];

if ($name === '') {
    $errors['name'] = "Name is mandatory.";
}

if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
    $errors['gender'] = "Gender is mandatory.";
}

if ($standard === '') {
    $errors['standard'] = "Standard is mandatory.";
}

$age = null;
if ($dob === '') {
    $errors['dob'] = "Date of birth is mandatory.";
} else {
    $dobDate = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$dobDate || $dobDate->format('Y-m-d') !== $dob) {
        $errors['dob'] = "Date of birth is invalid.";
    } else {
        $today = new DateTime();
        if ($dobDate > $today) {
            $errors['dob'] = "Date of birth cannot be in the future.";
        } else {
            $age = $today->diff($dobDate)->y;
        }
    }
}

if ($father_name === '') {
    $errors['father_name'] = "Father name is mandatory.";
}

if ($father_mobile === '') {
    $errors['father_mobile'] = "Father mobile number is mandatory.";
} elseif (!preg_match('/^[0-9]{10}$/', $father_mobile)) {
    $errors['father_mobile'] = "Mobile number must be exactly 10 numeric digits.";
}

if ($email === '') {
    $errors['email'] = "Email is mandatory.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Email is invalid.";
}

if (!empty($errors)) {
    respond(false, "Please fix the highlighted errors.", $errors);
}

$stmt = mysqli_prepare($conn, "INSERT INTO students (name, gender, standard, dob, age, father_name, father_mobile, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
// types: name(s) gender(s) standard(s) dob(s - DATE column accepts 'Y-m-d' string) age(i) father_name(s) father_mobile(s) email(s)
mysqli_stmt_bind_param($stmt, "ssssisss", $name, $gender, $standard, $dob, $age, $father_name, $father_mobile, $email);

if (mysqli_stmt_execute($stmt)) {
    respond(true, "Student added successfully.");
} else {
    respond(false, "Database error: " . mysqli_error($conn));
}
