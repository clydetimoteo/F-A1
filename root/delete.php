<?php
require_once '../config/Database.php';
$conn = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("DELETE FROM students WHERE StudentID IN ($placeholders)");
    $stmt->execute($ids);
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE StudentID = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;
