<?php
header('Content-Type: application/json');
require_once 'db.php';

$result = mysqli_query($conn, "SELECT id, name, gender, standard, dob, age, father_name, father_mobile, email FROM students ORDER BY id DESC");

$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

echo json_encode(["success" => true, "data" => $students]);
