<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veteriner Takip Sistemi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="data.php">Hayvan Bilgileri</a></li>
                <li><a href="examinations.php">Muayene Kayıtları</a></li>
                <li><a href="login.php?logout=1">Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Veteriner Takip Sistemine Hoşgeldiniz</h1>
        <p>Veteriner Takip Sistemi, veterinerler ve evcil hayvan sahiplerinin hayvanlarıyla ilgili önemli bilgileri yönetmelerine yardımcı olur. Bu sistemle şunları yapabilirsiniz:</p>
        <ul>
            <li>Evcil hayvan verilerini saklayabilir ve yönetebilirsiniz.</li>
            <li>Yaklaşan muayeneleri takip edebilirsiniz.</li>
            <li>İlaç takvimlerini yönetebilirsiniz.</li>
            <li>Evcil hayvanınızın sağlık durumunu zaman içinde izleyebilirsiniz.</li>
        </ul>
        <p>Yukarıdaki gezinme menüsünü kullanarak sistemin farklı özelliklerini keşfedebilirsiniz. Ayrıca, sistemin sağladığı kolaylıklarla hem evcil hayvanınızı daha sağlıklı tutabilir hem de sağlık geçmişini kolayca izleyebilirsiniz.</p>

        <h2>Sevgili Evcil Hayvanlarımız</h2>
        <div class="pet-gallery">
            <div class="pet">
                <img src="dog.jpg" alt="Köpek" />
                <h3>Köpek</h3>
                <p>Köpekler, sadakatleri ve arkadaşlıklarıyla bilinirler. Genellikle "insanın en iyi arkadaşı" olarak tanımlanırlar. Köpekler, eğitilebilir olmaları ve insanlarla güçlü bir bağ kurabilmeleri nedeniyle evcil hayvan olarak tercih edilir.</p>
            </div>
            <div class="pet">
                <img src="cat.jpg" alt="Kedi" />
                <h3>Kedi</h3>
                <p>Kediler, bağımsız ve oyuncu yaratıklardır. Şefkatli doğaları ve oyuncu halleriyle sevilirler. Kediler, insanlara karşı oldukça duygusal bağlar kurabilir ve evdeki huzurlu atmosferi desteklerler.</p>
            </div>
            <div class="pet">
                <img src="rabbit.jpg" alt="Tavşan" />
                <h3>Tavşan</h3>
                <p>Tavşanlar, nazik ve sosyal hayvanlardır. Yumuşak kürkleri ve oyuncu davranışlarıyla ünlüdürler. Tavşanlar, sakin yapılarıyla özellikle çocuklu aileler için uygun evcil hayvanlardır.</p>
            </div>
            <div class="pet">
                <img src="bird.jpg" alt="Kuş" />
                <h3>Kuş</h3>
                <p>Kuşlar, rengarenk ve zeki evcil hayvanlardır. Sosyal yapılarıyla dikkat çekerler ve sahipleriyle etkileşimde bulunmayı severler. Ayrıca, şarkı söyleme yetenekleri sayesinde evin içinde neşeli bir atmosfer oluştururlar.</p>
            </div>
        </div>
    </div>
	<footer>
        <p>&copy; 2025 Ömer Ali Adalı tarafından tasarlandı.</p>
    </footer>
</body>
</html>
