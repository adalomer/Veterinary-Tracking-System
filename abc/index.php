<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinary Tracking System</title>
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
        <h1>Welcome to the Veterinary Tracking System</h1>
        <p>Our Veterinary Tracking System helps veterinarians and pet owners manage important information about their animals. With our system, you can:</p>
        <ul>
            <li>Store and manage pet data</li>
            <li>Track upcoming examinations</li>
            <li>Manage medication schedules</li>
            <li>Monitor pet health over time</li>
        </ul>
        <p>Use the navigation menu above to explore the different features of our system.</p>

        <h2>Our Beloved Pets</h2>
        <div class="pet-gallery">
            <div class="pet">
                <img src="dog.jpg" alt="Dog" />
                <h3>Dog</h3>
                <p>Dogs are known for their loyalty and companionship. They are often referred to as "man's best friend."</p>
            </div>
            <div class="pet">
                <img src="cat.jpg" alt="Cat" />
                <h3>Cat</h3>
                <p>Cats are independent and playful creatures. They are loved for their affectionate nature and playful antics.</p>
            </div>
            <div class="pet">
                <img src="rabbit.jpg" alt="Rabbit" />
                <h3>Rabbit</h3>
                <p>Rabbits are gentle and social animals. They make great pets and are known for their soft fur and playful behavior.</p>
            </div>
            <div class="pet">
                <img src="bird.jpg" alt="Bird" />
                <h3>Bird</h3>
                <p>Birds are colorful and intelligent pets. They can be very social and enjoy interacting with their owners.</p>
            </div>
        </div>
    </div>
</body>
</html>
