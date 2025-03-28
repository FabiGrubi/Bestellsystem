<?php

/** @var PDO $pdo */
require dirname(__DIR__) . '/connect/connect.php';

// Artikel abrufen
$stmt = $pdo->prepare('SELECT * FROM `artikel`');
$stmt->execute();
$result_artikel = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bestellungen abrufen
$stmt = $pdo->prepare('SELECT * FROM `bestellung`');
$stmt->execute();
$result_bestellung = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bestelldetails abrufen
$id_artikel = '';
$id_bestellung = '';
$menge = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare('SELECT * FROM `bestelldetails` WHERE `id`=:id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        die("Bestelldetail nicht gefunden.");
    }

    $id_artikel = $result['id_artikel'];
    $id_bestellung = $result['id_bestellung'];
    $menge = $result['menge'];
}

// Update-Logik
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'];
    $id_artikel = $_POST['id_artikel'];
    $id_bestellung = $_POST['id_bestellung'];
    $menge = $_POST['menge'];

    $stmt = $pdo->prepare("UPDATE `bestelldetails` SET `id_artikel`=:id_artikel, `id_bestellung`=:id_bestellung, `menge`=:menge WHERE `id`=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':id_artikel', $id_artikel, PDO::PARAM_INT);
    $stmt->bindValue(':id_bestellung', $id_bestellung, PDO::PARAM_INT);
    $stmt->bindValue(':menge', $menge, PDO::PARAM_INT);
    $stmt->execute();
    
    header('Location: bestelldetails.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelldetail Ändern</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: rgb(245, 245, 245);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: rgb(8, 0, 234);
            padding-top: 20px;
        }

        .form-container {
            width: 50%;
            margin: 30px auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-update {
            background-color: rgb(23, 0, 234);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-update:hover {
            background-color: rgb(27, 0, 179);
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h1>Bestelldetail bearbeiten</h1>
        <a href="bestelldetails.php" class="btn-update">Zurück zur Übersicht</a>
    </div>
    <div class="form-container">
        <form method="POST">
            <label for="id_artikel">Artikel</label>
            <select id="id_artikel" name="id_artikel" required>
                <option value="">Bitte wählen</option>
                <?php foreach ($result_artikel as $artikel): ?>
                    <option value="<?php echo $artikel['id']; ?>" <?php echo ($artikel['id'] == $id_artikel) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($artikel['bezeichnung']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_bestellung">Bestellung</label>
            <select id="id_bestellung" name="id_bestellung" required>
                <option value="">Bitte wählen</option>
                <?php foreach ($result_bestellung as $bestellung): ?>
                    <option value="<?php echo $bestellung['id']; ?>" <?php echo ($bestellung['id'] == $id_bestellung) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($bestellung['id']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="menge">Menge</label>
            <input type="number" id="menge" name="menge" min="1" value="<?php echo htmlspecialchars($menge); ?>" required>

            <button type="submit" class="btn-update">Daten aktualisieren</button>
        </form>
    </div>
</body>
</html>