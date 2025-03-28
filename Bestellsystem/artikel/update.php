<?php
require dirname(__DIR__) . '/connect/connect.php';

// Abrufen der Artiekl-ID aus der URL
$id = $_GET['id'] ?? null;

// Überprüfen, ob eine gültige ID angegeben wurde
if (!$id) {
    die('Keine gültige ID angegeben.');
}

// Abrufen des Artikels aus der Datenbank
$stmt = $pdo->prepare('SELECT * FROM artikel WHERE id = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$artikel = $stmt->fetch(PDO::FETCH_ASSOC);

// Überprüfen, ob der Artikel existiert
if (!$artikel) {
    die('Artikel nicht gefunden.');
}

// Daten aktualisieren, wenn das Formular abgesendet wird
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bezeichnung = $_POST['bezeichnung'];
    $preis_netto = $_POST['preis_netto'];
    $ust_satz = $_POST['ust_satz'];

    // Validierung der Eingaben
    if (empty($bezeichnung) || empty($preis_netto) || empty($ust_satz)) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        // Update der Artikel-Daten in der Datenbank
        $updateStmt = $pdo->prepare('UPDATE artikel SET bezeichnung = :bezeichnung, preis_netto = :preis_netto, ust_satz = :ust_satz WHERE id = :id');
        $updateStmt->bindParam(':bezeichnung', $bezeichnung);
        $updateStmt->bindParam(':preis_netto', $preis_netto);
        $updateStmt->bindParam(':ust_satz', $ust_satz);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStmt->execute();

        // Weiterleitung zur Übersicht
        header('Location: artikel.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel bearbeiten</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color:rgb(245, 245, 245);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color:rgb(8, 0, 234);
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

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-update {
            background-color:rgb(23, 0, 234);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-update:hover {
            background-color:rgb(27, 0, 179);
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
        <h1>Artikel bearbeiten</h1>
        <a href="artikel.php" class="btn-update">Zurück zur Übersicht</a>
    </div>

    <div class="form-container">
        <form method="POST">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <label for="bezeichnung">Bezeichnung</label>
            <input type="text" id="bezeichnung" name="bezeichnung" value="<?php echo htmlspecialchars($artikel['bezeichnung']); ?>" required>

            <label for="preis_netto">Preis Netto</label>
            <input type="number" id="preis_netto" name="preis_netto" value="<?php echo htmlspecialchars($artikel['preis_netto']); ?>" required>

            <label for="ust_satz">Ust Satz</label>
            <input type="number" id="ust_satz" name="ust_satz" value="<?php echo htmlspecialchars($artikel['ust_satz']); ?>" required>

            <button type="submit" class="btn-update">Daten aktualisieren</button>
        </form>
    </div>
</body>
</html>
