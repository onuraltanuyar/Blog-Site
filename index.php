<?php
session_start(); // Oturum başlatma

// Veritabanı bağlantı bilgileri
$host = 'localhost';
$dbname = 'sosyal_blog';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // İçerik ekleme işlemi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_content'])) {
        $icerik_basligi = $_POST['icerik_basligi'];
        $icerik_metni = $_POST['icerik_metni'];

        // SQL sorgusu ile veritabanına içerik eklemek
        $stmt = $pdo->prepare("INSERT INTO icerikler (baslik, metin, tarih) VALUES (:baslik, :metin, NOW())");
        $stmt->bindParam(':baslik', $icerik_basligi);
        $stmt->bindParam(':metin', $icerik_metni);
        $stmt->execute();
    }

    // İçerikleri veritabanından çekme
    $icerikler = $pdo->query("SELECT * FROM icerikler ORDER BY tarih DESC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sosyal Blog</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    position: relative;
    min-height: 100vh;
    padding-bottom: 100px; /* Footer yüksekliği kadar alttan boşluk bırak */
    }
    header {
    background-color: #4CAF50; /* Yeşil arka plan rengi */
    color: white;
    text-align: center;
    padding: 2em 0;
    animation: headerAnimation 1s ease-in-out;
    }

    @keyframes headerAnimation {
        0% { transform: translateY(-100px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    header h1 {
        margin: 0;
        font-size: 3em;
        font-weight: bold;
        animation: fadeIn 2s;
    }

    header p {
        font-size: 1.5em;
        margin-top: 0.5em;
        animation: fadeIn 2s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    nav {
        text-align: center;
        padding: 1em 0;
    }
    nav a {
        color: white;
        text-decoration: none;
        padding: 0 10px;
        font-size: 1.2em;
    }
    main {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    article {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    footer {
        background-color: #4CAF50; /* Yeşil arka plan rengi */
        color: white;
        text-align: center;
        padding: 1em 0;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100px; /* Footer yüksekliği */
    }
    .social-buttons {
        margin-top: 10px;
    }
    .social-buttons button {
        background-color: #1da1f2;
        color: white;
        border: none;
        padding: 10px 20px;
        margin-right: 5px;
        cursor: pointer;
    }
    .social-buttons button.facebook {
        background-color: #4267b2;
    }

    input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* Kutucuğun genişliğini padding dahil hesaplar */
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Butona tıkladığında renk değişim efekti */
    }

    button.twitter {
        background-color: #1da1f2;
        color: white;
    }

    button.twitter:hover {
        background-color: #0d8bf2; /* Butonun üzerine gelindiğinde renk değişimi */
    }

    button.facebook {
        background-color: #4267b2;
        color: white;
    }

    button.facebook:hover {
        background-color: #365899; /* Butonun üzerine gelindiğinde renk değişimi */
    }

    .submit {
    background-color: #4CAF50; /* Yeşil arka plan rengi */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }

    .submit:hover {
        background-color: #45a049; /* Butonun üzerine gelindiğinde koyulaşan yeşil renk */
    }


    button.submit {
    background-color: #4CAF50; /* Yeşil arka plan rengi */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }

    button.submit:hover {
        background-color: #45a049; /* Butonun üzerine gelindiğinde koyulaşan yeşil renk */
    }

    #icerik_metni {
    width: 100%; /* Genişliği konteynerin tamamını kaplasın */
    height: 150px; /* Yükseklik değeri */
    padding: 10px; /* İçerikten kenarlara boşluk */
    border: 1px solid #ccc; /* Kenarlık stilini belirle */
    border-radius: 4px; /* Kenarları yuvarlak yap */
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); /* İç gölge efekti */
    resize: none; /* Kullanıcının boyutlandırmasını engelle */
    }


    </style>
</head>
<body>
    <header>
        <h1>Mainersy Blog</h1>
        <nav>
            <a>Blog Sitemize Hos geldiniz!  </a>   
        </nav>
    </header>
    <main>
        <!-- İçerik ekleme formu -->
        <form action="" method="post">

            <label for="icerik_basligi">İçerik Başlığı:</label>
            <input type="text" id="icerik_basligi" name="icerik_basligi" required><br><br>

            <label for="icerik_metni">İçerik Metni:</label>

            <textarea id="icerik_metni" name="icerik_metni" draggable="false" required></textarea><br><br>

            <input type="submit" name="submit_content" value="İçerik Ekle" class="submit">

        </form>

        <!-- İçerikleri listeleme -->
        <?php foreach($icerikler as $icerik): ?>
        <article>
            <h2><?php echo htmlspecialchars($icerik['baslik']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($icerik['metin'])); ?></p>
            <!-- Sosyal medyada paylaşma butonları -->
            <button onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('<?php echo $icerik['baslik']; ?>'));">Twitter'da Paylaş</button>
            <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href));">Facebook'ta Paylaş</button>
        </article>
        <?php endforeach; ?>
    </main>
    <footer>
        <p>© 2024 Mainersy, Onur Altan Uyar - Tüm hakları saklıdır.</p>
    </footer>
    
</body>
</html>