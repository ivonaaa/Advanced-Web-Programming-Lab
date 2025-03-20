<?php
// Učitavanje XML datoteke
$xml_file = "LV2.xml";
if (!file_exists($xml_file)) {
    die("XML datoteka nije pronađena.");
}

$xml = simplexml_load_file($xml_file);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Popis osoba</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .person-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h2>Popis osoba iz XML-a</h2>
    <div class="container">
        <?php foreach ($xml->record as $osoba): ?>
            <div class="person-card">
                <img src="<?= htmlspecialchars($osoba->slika) ?>" alt="Slika">
                <h3><?= htmlspecialchars($osoba->ime) ?> <?= htmlspecialchars($osoba->prezime) ?></h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($osoba->email) ?></p>
                <p><?= htmlspecialchars($osoba->zivotopis) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
