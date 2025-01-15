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

    // Prepare the insert query
    $stmt = $pdo->prepare("INSERT INTO examinations (pet_id, exam_date, medication, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pet_id, $exam_date, $medication, $start_date, $end_date]);
}

// Fetch the examinations data for the logged-in user
$stmt = $pdo->prepare("SELECT p.pet_name, e.* FROM examinations e JOIN pets p ON e.pet_id = p.pet_id WHERE p.user_id = ? ORDER BY e.exam_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$examinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the pets of the logged-in user for the dropdown
$stmt = $pdo->prepare("SELECT pet_id, pet_name FROM pets WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muayene takip - Veteriner takip sistemi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana sayfa</a></li>
                <li><a href="data.php">Hayvan bilgileri</a></li>
                <li><a href="examinations.php">Muayene kayıtları</a></li>
                <li><a href="login.php?logout=1">Çıkış yap</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Muayene ve İlaç Takibi</h1>

        <!-- Add Examination Form -->
        <form action="examinations.php" method="post">
            <label for="pet_id">Hayvan ismi:</label>
            <select id="pet_id" name="pet_id" required>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?= $pet['pet_id'] ?>"><?= htmlspecialchars($pet['pet_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="exam_date">Muayene zamanı:</label>
            <input type="date" id="exam_date" name="exam_date" required>

            <label for="medication">İlaç:</label>
            <input type="text" id="medication" name="medication" required>

            <label for="start_date">Başlangıç tarihi:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Bitiş tarihi:</label>
            <input type="date" id="end_date" name="end_date" required>

            <input type="submit" value="Muayene ve ilaç bilgilerini ekle">
        </form>

        <h2>Yaklaşan Muayeneler ve İlaçlar</h2>

        <?php if (empty($examinations)): ?>
            <p>Hiçbir muayene bulunamadı. Lütfen yeni bir muayene ekleyin.</p>
        <?php else: ?>
            <?php 
            $today = new DateTime();
            foreach ($examinations as $exam): 
                $exam_date = new DateTime($exam['exam_date']);
                $days_left = $today->diff($exam_date)->days;
            ?>
                <div class="exam-card">
                    <h3>Hayvan: <?= htmlspecialchars($exam['pet_name']) ?></h3>
                    <p>Muayene tarihi: <?= $exam['exam_date'] ?></p>
                    <p>Kalan gün sayısı: <?= $days_left ?></p>
                    <p>İlaç: <?= htmlspecialchars($exam['medication']) ?></p>
                    <p>Başlangıç tarihi: <?= $exam['start_date'] ?></p>
                    <p>Bitiş tarihi: <?= $exam['end_date'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
	<footer>
        <p>&copy; 2025 Ömer Ali Adalı tarafından tasarlandı.</p>
    </footer>
</body>
</html>
