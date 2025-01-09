<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];

    $stmt = $pdo->prepare("INSERT INTO pets (user_id, pet_name, species, breed, age, weight) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $pet_name, $species, $breed, $age, $weight]);
}

$stmt = $pdo->prepare("SELECT * FROM pets WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$pets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Data - Veterinary Tracking System</title>
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
        <h1>Pet Data Management</h1>
        <form action="data.php" method="post">
            <label for="pet_name">Pet Name:</label>
            <input type="text" id="pet_name" name="pet_name" required>

            <label for="species">Species:</label>
            <input type="text" id="species" name="species" required>

            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed">

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="weight">Weight (kg):</label>
            <input type="number" step="0.1" id="weight" name="weight" required>

            <input type="submit" value="Add Pet">
        </form>

        <h2>Your Pets</h2>
        <?php foreach ($pets as $pet): ?>
            <div class="pet-card">
                <h3><?= htmlspecialchars($pet['pet_name']) ?></h3>
                <p>Species: <?= htmlspecialchars($pet['species']) ?></p>
                <p>Breed: <?= htmlspecialchars($pet['breed']) ?></p>
                <p>Age: <?= htmlspecialchars($pet['age']) ?></p>
                <p>Weight: <?= htmlspecialchars($pet['weight']) ?> kg</p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>