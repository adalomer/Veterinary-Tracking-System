<?php
session_start();
require_once 'config.php';

// Admin control
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch users and animals
$users = $pdo->query("SELECT id, username FROM users WHERE is_admin = FALSE")->fetchAll(PDO::FETCH_ASSOC);
$animals = $pdo->query("SELECT * FROM pets")->fetchAll(PDO::FETCH_ASSOC);

// Fetch examination records
$examinations = $pdo->query("
    SELECT e.pet_id, e.exam_date, e.medication, e.start_date, e.end_date, p.pet_name
    FROM examinations e
    JOIN pets p ON e.pet_id = p.pet_id
")->fetchAll(PDO::FETCH_ASSOC);

// Delete user
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        header("Location: admin_panel.php?success=user_deleted");
    } else {
        header("Location: admin_panel.php?error=user_delete_failed");
    }
    exit();
}

// Update animal treatment status
if (isset($_POST['update_animal'])) {
    $animal_id = intval($_POST['animal_id']);
    $treatment_status = $_POST['treatment_status'];

    $stmt = $pdo->prepare("UPDATE pets SET treatment_status = ? WHERE pet_id = ?");
    if ($stmt->execute([$treatment_status, $animal_id])) {
        header("Location: admin_panel.php?success=animal_updated");
    } else {
        header("Location: admin_panel.php?error=animal_update_failed");
    }
    exit();
}

// Edit animal details
if (isset($_POST['edit_animal'])) {
    $animal_id = intval($_POST['animal_id']);
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $age = intval($_POST['age']);
    $weight = intval($_POST['weight']);

    $stmt = $pdo->prepare("UPDATE pets SET pet_name = ?, species = ?, age = ?, weight = ? WHERE pet_id = ?");
    if ($stmt->execute([$pet_name, $species, $age, $weight, $animal_id])) {
        header("Location: admin_panel.php?success=animal_edited");
    } else {
        header("Location: admin_panel.php?error=animal_edit_failed");
    }
    exit();
}

// Add animal
if (isset($_POST['add_animal'])) {
    $username = $_POST['username'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = intval($_POST['age']);
    $weight = intval($_POST['weight']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['id'];
        $stmt = $pdo->prepare("INSERT INTO pets (user_id, pet_name, species, breed, age, weight, treatment_status) 
                               VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        if ($stmt->execute([$user_id, $pet_name, $species, $breed, $age, $weight])) {
            header("Location: admin_panel.php?success=animal_added");
        } else {
            header("Location: admin_panel.php?error=animal_add_failed");
        }
    } else {
        header("Location: admin_panel.php?error=user_not_found");
    }
    exit();
}

// Delete animal
if (isset($_POST['delete_animal'])) {
    $animal_id = intval($_POST['animal_id']);
    $stmt = $pdo->prepare("DELETE FROM pets WHERE pet_id = ?");
    if ($stmt->execute([$animal_id])) {
        header("Location: admin_panel.php?success=animal_deleted");
    } else {
        header("Location: admin_panel.php?error=animal_delete_failed");
    }
    exit();
}

// Add examination
if (isset($_POST['add_examination'])) {
    $pet_id = intval($_POST['pet_id']);
    $exam_date = $_POST['exam_date'];
    $medication = $_POST['medication'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $pdo->prepare("INSERT INTO examinations (pet_id, exam_date, medication, start_date, end_date) 
                           VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$pet_id, $exam_date, $medication, $start_date, $end_date])) {
        header("Location: admin_panel.php?success=examination_added");
    } else {
        header("Location: admin_panel.php?error=examination_add_failed");
    }
    exit();
}

// Update examination
if (isset($_POST['update_examination'])) {
    $examination_id = intval($_POST['examination_id']);
    $exam_date = $_POST['exam_date'];
    $medication = $_POST['medication'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $pdo->prepare("
        UPDATE examinations 
        SET exam_date = ?, medication = ?, start_date = ?, end_date = ? 
        WHERE pet_id = ?
    ");
    if ($stmt->execute([$exam_date, $medication, $start_date, $end_date, $examination_id])) {
        header("Location: admin_panel.php?success=examination_updated");
    } else {
        header("Location: admin_panel.php?error=examination_update_failed");
    }
    exit();
}

// Delete examination
if (isset($_POST['delete_examination'])) {
    $examination_id = intval($_POST['examination_id']);
    $stmt = $pdo->prepare("DELETE FROM examinations WHERE pet_id = ?");
    if ($stmt->execute([$examination_id])) {
        header("Location: admin_panel.php?success=examination_deleted");
    } else {
        header("Location: admin_panel.php?error=examination_delete_failed");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim paneli - Veteriner takip sistemi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Yönetim paneli</h1>

        <!-- Logout Button -->
        <form action="logout.php" method="post" class="logout-form">
            <button type="submit" class="btn logout">Çıkış yap</button>
        </form>

        <!-- Success and Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="Başarılı"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="Hata oluştu"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Users List -->
        <section>
            <h2>Kullanıcılar</h2>
            <ul class="user-list">
                <?php foreach ($users as $user): ?>
                    <li>
                        <?= htmlspecialchars($user['username']); ?>
                        <form action="admin_panel.php" method="post" class="inline-form">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="delete_user" class="btn delete">Kullanıcıyı sil</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Add Animal -->
        <section>
            <h2>Hayvan ekle</h2>
            <form action="admin_panel.php" method="post">
                <label for="username">Kullanıcı ismi:</label>
                <input type="text" id="username" name="username" required>

                <label for="pet_name">Hayvan ismi:</label>
                <input type="text" id="pet_name" name="pet_name" required>

                <label for="species">Türü:</label>
                <input type="text" id="species" name="species" required>

                <label for="breed">Cinsiyeti:</label>
                <input type="text" id="breed" name="breed">

                <label for="age">Yaşı:</label>
                <input type="number" id="age" name="age" required>

                <label for="weight">Ağırlığı (kg):</label>
                <input type="number" id="weight" name="weight" required>

                <button type="submit" name="add_animal" class="btn add">Hayvanı ekle</button>
            </form>
        </section>

        <!-- Animals List -->
        <section>
            <h2>Hayvan bilgileri ve tedavi durumu</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>İsim</th>
                        <th>Türü</th>
                        <th>Yaşı</th>
                        <th>Ağırlığı</th>
                        <th>Tedavi durumu</th>
                        <th>Düzenleme</th>
                        <th>Silme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($animals as $animal): ?>
                        <tr>
                            <td><?= htmlspecialchars($animal['pet_id']); ?></td>
                            <td><?= htmlspecialchars($animal['pet_name']); ?></td>
                            <td><?= htmlspecialchars($animal['species']); ?></td>
                            <td><?= htmlspecialchars($animal['age']); ?></td>
                            <td><?= htmlspecialchars($animal['weight']); ?></td>
                            <td>
                                <form action="admin_panel.php" method="post">
                                    <input type="hidden" name="animal_id" value="<?= $animal['pet_id']; ?>">
                                    <select name="treatment_status">
                                        <option value="Pending" <?= ($animal['treatment_status'] === 'Pending') ? 'selected' : ''; ?>>Tedavi oluyor</option>
                                        <option value="Completed" <?= ($animal['treatment_status'] === 'Completed') ? 'selected' : ''; ?>>Tedavi tamamlandı</option>
                                    </select>
                                    <button type="submit" name="update_animal" class="btn update">Güncelle</button>
                                </form>
                            </td>
                            <td>
                                <form action="admin_panel.php" method="post">
                                    <input type="hidden" name="animal_id" value="<?= $animal['pet_id']; ?>">
                                    <label for="pet_name_<?= $animal['pet_id']; ?>">İsim:</label>
                                    <input type="text" id="pet_name_<?= $animal['pet_id']; ?>" name="pet_name" value="<?= htmlspecialchars($animal['pet_name']); ?>">

                                    <label for="species_<?= $animal['pet_id']; ?>">Türü:</label>
                                    <input type="text" id="species_<?= $animal['pet_id']; ?>" name="species" value="<?= htmlspecialchars($animal['species']); ?>">

                                    <label for="age_<?= $animal['pet_id']; ?>">Yaşı:</label>
                                    <input type="number" id="age_<?= $animal['pet_id']; ?>" name="age" value="<?= htmlspecialchars($animal['age']); ?>">

                                    <label for="weight_<?= $animal['pet_id']; ?>">Ağırlığı:</label>
                                    <input type="number" id="weight_<?= $animal['pet_id']; ?>" name="weight" value="<?= htmlspecialchars($animal['weight']); ?>">

                                    <button type="submit" name="edit_animal" class="btn edit">Düzenle</button>
                                </form>
                            </td>
                            <td>
                                <form action="admin_panel.php" method="post">
                                    <input type="hidden" name="animal_id" value="<?= $animal['pet_id']; ?>">
                                    <button type="submit" name="delete_animal" class="btn delete">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
		<!-- Examinations List -->
<section>
    <h2>Muayene Kayıtları</h2>
    <table>
        <thead>
            <tr>
                <th>Hayvan Adı</th>
                <th>Muayene Tarihi</th>
                <th>İlaç</th>
                <th>Başlangıç Tarihi</th>
                <th>Bitiş Tarihi</th>
                <th>Düzenleme</th>
                <th>Silme</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($examinations as $examination): ?>
                <tr>
                    <form action="admin_panel.php" method="post">
                        <td><?= htmlspecialchars($examination['pet_name']); ?></td>
                        <td>
                            <input type="date" name="exam_date" value="<?= htmlspecialchars($examination['exam_date']); ?>">
                        </td>
                        <td>
                            <input type="text" name="medication" value="<?= htmlspecialchars($examination['medication']); ?>">
                        </td>
                        <td>
                            <input type="date" name="start_date" value="<?= htmlspecialchars($examination['start_date']); ?>">
                        </td>
                        <td>
                            <input type="date" name="end_date" value="<?= htmlspecialchars($examination['end_date']); ?>">
                        </td>
                        <td>
                            <input type="hidden" name="examination_id" value="<?= $examination['pet_id']; ?>">
                            <button type="submit" name="update_examination" class="btn update">Güncelle</button>
                        </td>
                    </form>
                    <td>
                        <form action="admin_panel.php" method="post">
                            <input type="hidden" name="examination_id" value="<?= $examination['pet_id']; ?>">
                            <button type="submit" name="delete_examination" class="btn delete">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

    </div>
	<footer>
        <p>&copy; 2025 Ömer Ali Adalı tarafından tasarlandı.</p>
    </footer>
</body>
</html>
