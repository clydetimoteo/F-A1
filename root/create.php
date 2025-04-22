<?php
require_once '../config/Database.php';
$conn = (new Database())->getConnection();

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
        $targetFilePath = "https://www.w3schools.com/howto/img_avatar.png";
    }

    $stmt = $conn->prepare("INSERT INTO students (ProfilePicture, FirstName, LastName, MiddleInitial, Age, Gender, Email, Address) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$targetFilePath, $firstName, $lastName, $middleInitial, $age, $gender, $email, $address]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Create Student</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" required class="form-control">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" required class="form-control">
        </div>
        <div class="form-group">
            <label>Middle Initial</label>
            <input type="text" name="middle_initial" maxlength="5" class="form-control">
        </div>
        <div class="form-group">
            <label>Age</label>
            <input type="number" name="age" required class="form-control">
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required class="form-control">
                <option value="" disabled selected>-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
