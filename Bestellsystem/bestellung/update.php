<?php
require dirname(__DIR__) . '/connect/connect.php';

// Funktion zum sicheren Abrufen von GET-Parametern
function getParam($paramName, $default = null) {
    return isset($_GET[$paramName]) ? filter_input(INPUT_GET, $paramName, FILTER_SANITIZE_NUMBER_INT) : $default;
}

// Funktion zum sicheren Abrufen von POST-Parametern
function postParam($paramName, $default = null) {
    return isset($_POST[$paramName]) ? filter_input(INPUT_POST, $paramName, FILTER_SANITIZE_SPECIAL_CHARS) : $default;
}

// Abrufen der Bestellung-ID aus der URL
$id = getParam('id');

// Überprüfen, ob eine gültige ID angegeben wurde
if (!$id) {
    die('Keine gültige ID angegeben.');
}

// Abrufen der Bestellung aus der Datenbank
$stmt = $pdo->prepare('SELECT * FROM bestellung WHERE id = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$bestellung = $stmt->fetch(PDO::FETCH_ASSOC);

// Überprüfen, ob die Bestellung existiert
if (!$bestellung) {
    die('Bestellung nicht gefunden.');
}

// Abrufen der Kunden für das Dropdown
$customerStmt = $pdo->query('SELECT id, nachname, vorname FROM kunden');
$result_customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

// Daten aktualisieren, wenn das Formular abgesendet wird
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kunde_id = postParam('kunde_id');
    $datum = postParam('datum');

    // Validierung der Eingaben
    if (empty($kunde_id) || empty($datum)) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        // Bestellung aktualisieren
        $updateStmt = $pdo->prepare('UPDATE bestellung SET id_kunde = :kunde_id, datum = :datum WHERE id = :id');
        $updateStmt->bindParam(':kunde_id', $kunde_id, PDO::PARAM_INT);
        $updateStmt->bindParam(':datum', $datum);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStmt->execute();

        // Weiterleitung zur Übersicht
        header('Location: bestellung.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung bearbeiten</title>
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

        input[type="date"],
        select {
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
        <h1>Bestellung bearbeiten</h1>
        <a href="bestellung.php" class="btn-update">Zurück zur Übersicht</a>
    </div>

    <div class="form-container">
        <form method="POST">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <label for="kunde_id">Kunde</label>
            <select id="kunde_id" name="kunde_id" required>
                <option value="">Bitte wählen</option>
                <?php foreach ($result_customers as $result): ?>
                    <option value="<?php echo $result['id']; ?>" <?php echo ($result['id'] == $bestellung['id_kunde']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($result['nachname'] . " " . $result['vorname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="datum">Datum</label>
            <input type="date" id="datum" name="datum" value="<?php echo htmlspecialchars($bestellung['datum']); ?>" required>

            <button type="submit" class="btn-update">Daten aktualisieren</button>
        </form>
    </div>
</body>
</html>
