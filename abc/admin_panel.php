<?php
session_start();
require_once 'config.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch users and animals
$users = $pdo->query("SELECT id, username FROM users WHERE is_admin = FALSE")->fetchAll(PDO::FETCH_ASSOC);
$animals = $pdo->query("SELECT * FROM pets")->fetchAll(PDO::FETCH_ASSOC);

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
    $stmt = $pdo->prepare("UPDATE pets SET treatment_status = ? WHERE id = ?");
    if ($stmt->execute([$treatment_status, $animal_id])) {
        header("Location: admin_panel.php?success=animal_updated");
    } else {
        header("Location: admin_panel.php?error=animal_update_failed");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Veterinary System</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>

        <!-- Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Operation completed successfully.</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error">An error occurred during the operation.</p>
        <?php endif; ?>

        <!-- Users Section -->
        <section>
            <h2>Users</h2>
            <ul class="user-list">
                <?php foreach ($users as $user): ?>
                    <li>
                        <?= htmlspecialchars($user['username']); ?>
                        <form action="admin_panel.php" method="post" class="inline-form">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="delete_user" class="btn delete">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Animals Section -->
        <section>
            <h2>Animal Treatments</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Animal Name</th>
                        <th>Treatment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?= $animal['id']; ?></td>
                        <td><?= htmlspecialchars($animal['name']); ?></td>
                        <td><?= htmlspecialchars($animal['treatment_status']); ?></td>
                        <td>
                            <form action="admin_panel.php" method="post">
                                <input type="hidden" name="animal_id" value="<?= $animal['id']; ?>">
                                <select name="treatment_status">
                                    <option value="Pending" <?= $animal['treatment_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Completed" <?= $animal['treatment_status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <button type="submit" name="update_animal" class="btn update">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
