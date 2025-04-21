<?php
require_once '../config/Database.php';
$conn = (new Database())->getConnection();

$stmt = $conn->prepare("SELECT * FROM students ORDER BY StudentID DESC");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function toggleSelectAll(source) {
            checkboxes = document.getElementsByName('selected_ids[]');
            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function confirmBulkDelete() {
            return confirm("Are you sure you want to delete the selected students?");
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>Student Management System</h2>
    <a href="create.php" class="btn btn-success mb-3">Add New Student</a>

    <form method="POST" action="delete.php" onsubmit="return confirmBulkDelete();">
        <button type="submit" class="btn btn-danger mb-2">Delete Selected</button>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                    <th>Profile Picture</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Initial</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $student['StudentID'] ?>"></td>
                        <td>
                            <?php
                                $profilePicture = $student['ProfilePicture'];
                                $gender = strtolower($student['Gender']);

                                if (empty($profilePicture)) {
                                    if ($gender === 'female') {
                                        $profilePicture = 'https://www.w3schools.com/howto/img_avatar2.png';
                                    } else {
                                        $profilePicture = 'https://www.w3schools.com/howto/img_avatar.png';
                                    }
                                }
                            ?>
                            <img src="<?= htmlspecialchars($profilePicture) ?>" width="60" height="60" style="object-fit: cover; border-radius: 50%;">
                        </td>
                        <td><?= htmlspecialchars($student['FirstName']) ?></td>
                        <td><?= htmlspecialchars($student['LastName']) ?></td>
                        <td><?= htmlspecialchars($student['MiddleInitial']) ?></td>
                        <td><?= htmlspecialchars($student['Age']) ?></td>
                        <td><?= htmlspecialchars($student['Gender']) ?></td>
                        <td><?= htmlspecialchars($student['Email']) ?></td>
                        <td><?= htmlspecialchars($student['Address']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $student['StudentID'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?id=<?= $student['StudentID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No students found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
</body>
</html>
