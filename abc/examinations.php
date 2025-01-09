<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_id = $_POST['pet_id'];
    $exam_date = $_POST['exam_date'];
    $medication = $_POST['medication'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $pdo->prepare("INSERT INTO examinations (pet_id, exam_date, medication, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pet_id, $exam_date, $medication, $start_date, $end_date]); 
}

$stmt = $pdo->prepare("SELECT p.pet_name, e.* FROM examinations e JOIN pets p ON e.pet_id = p.id WHERE p.user_id = ? ORDER BY e.exam_date");
$stmt->execute([$_SESSION['user_id']]);
$examinations = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT id, pet_name FROM pets WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$pets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examinations - Veterinary Tracking System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="data.php">Pet Data</a></li>
                <li><a href="examinations.php">Examinations</a></li>
                <li><a href="login.php?logout=1">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Examinations and Medication Tracking</h1>
        <form action="examinations.php" method="post">
            <label for="pet_id">Pet:</label>
            <select id="pet_id" name="pet_id" required>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?= $pet['id'] ?>"><?= htmlspecialchars($pet['pet_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="exam_date">Examination Date:</label>
            <input type="date" id="exam_date" name="exam_date" required>

            <label for="medication">Medication:</label>
            <input type="text" id="medication" name="medication" required>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <input type="submit" value="Add Examination">
        </form>

        <h2>Upcoming Examinations and Medications</h2>
        <?php 
        $today = new DateTime();
        foreach ($examinations as $exam): 
            $exam_date = new DateTime($exam['exam_date']);
            $days_left = $today->diff($exam_date)->days;
        ?>
            <div class="exam-card">
                <h3>Pet: <?= htmlspecialchars($exam['pet_name']) ?></h3>
                <p>Examination Date: <?= $exam['exam_date'] ?></p>
                <p>Days Left: <?= $days_left ?></p>
                <p>Medication: <?= htmlspecialchars($exam['medication']) ?></p>
                <p>Start Date: <?= $exam['start_date'] ?></p>
                <p>End Date: <?= $exam['end_date'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>