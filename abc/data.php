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
    <title>Hayvan bilgileri - Veterine takip sistemi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="data.php">Hayvan Bilgileri</a></li>
                <li><a href="examinations.php">Muayene Kayıtları</a></li>
                <li><a href="login.php?logout=1">Çıkış yap</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Hayvan bilgi yönetimi</h1>
        <form action="data.php" method="post">
            <label for="pet_name">Hayvan ismi:</label>
            <input type="text" id="pet_name" name="pet_name" required>

            <label for="species">Türü:</label>
            <input type="text" id="species" name="species" required>

            <label for="breed">Cinsiyeti:</label>
            <input type="text" id="breed" name="breed">

            <label for="age">Yaşı:</label>
            <input type="number" id="age" name="age" required>

            <label for="weight">Ağırlığı (kg):</label>
            <input type="number" step="0.1" id="weight" name="weight" required>

            <input type="submit" value="Onayla">
        </form>

        <h2>Kayıtlı hayvanlarınız</h2>
        <?php foreach ($pets as $pet): ?>
            <div class="pet-card">
                <h3><?= htmlspecialchars($pet['pet_name']) ?></h3>
                <p>Türü: <?= htmlspecialchars($pet['species']) ?></p>
                <p>Cinsiyeti: <?= htmlspecialchars($pet['breed']) ?></p>
                <p>Yaşı: <?= htmlspecialchars($pet['age']) ?></p>
                <p>Ağrılığı: <?= htmlspecialchars($pet['weight']) ?> kg</p>
            </div>
        <?php endforeach; ?>
    </div>
	<footer>
        <p>&copy; 2025 Ömer Ali Adalı tarafından tasarlandı.</p>
    </footer>
</body>
</html>