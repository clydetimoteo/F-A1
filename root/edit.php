<?php
require_once '../config/Database.php';
$conn = (new Database())->getConnection();

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE StudentID = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) die("Student not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $middleInitial = $_POST['middle_initial'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath);
    } else {
        $targetFilePath = $student['ProfilePicture'];
    }

    $stmt = $conn->prepare("UPDATE students SET ProfilePicture=?, FirstName=?, LastName=?, MiddleInitial=?, Age=?, Gender=?, Email=?, Address=? WHERE StudentID=?");
    $stmt->execute([$targetFilePath, $firstName, $lastName, $middleInitial, $age, $gender, $email, $address, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Student</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Profile Picture</label><br>
            <img src="<?= $student['ProfilePicture'] ?>" width="70"><br>
            <input type="file" name="profile_picture" class="form-control mt-2">
        </div>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?= $student['FirstName'] ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= $student['LastName'] ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label>Middle Initial</label>
            <input type="text" name="middle_initial" value="<?= $student['MiddleInitial'] ?>" maxlength="5" class="form-control">
        </div>
        <div class="form-group">
            <label>Age</label>
            <input type="number" name="age" value="<?= $student['Age'] ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required class="form-control">
                <option value="Male" <?= $student['Gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $student['Gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $student['Gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= $student['Email'] ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required class="form-control"><?= $student['Address'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
