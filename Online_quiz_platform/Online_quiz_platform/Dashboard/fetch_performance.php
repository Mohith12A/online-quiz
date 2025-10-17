<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id']; // Assuming user is logged in

$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT subject, score, total FROM quiz_results WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$response = [
    "name" => $user['name'],
    "results" => $results
];

echo json_encode($response);
?>
