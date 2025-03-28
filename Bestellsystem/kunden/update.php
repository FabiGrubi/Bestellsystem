<?php
require dirname(__DIR__) . '/connect/connect.php';

// Abrufen der Kunden-ID aus der URL
$id = $_GET['id'] ?? null;

// Überprüfen, ob eine gültige ID angegeben wurde
if (!$id) {
    die('Keine gültige ID angegeben.');
}

// Abrufen des Kunden aus der Datenbank
$stmt = $pdo->prepare('SELECT * FROM kunden WHERE id = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$kunden = $stmt->fetch(PDO::FETCH_ASSOC);

// Überprüfen, ob der Kunde existiert
if (!$kunden) {
    die('Kunde nicht gefunden.');
}

// Daten aktualisieren, wenn das Formular abgesendet wird
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nachname = $_POST['nachname'];
    $vorname = $_POST['vorname'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];

    // Validierung der Eingaben
    if (empty($nachname) || empty($vorname) || empty($telefon) || empty($email)) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        // Update der Kunden-Daten in der Datenbank
        $updateStmt = $pdo->prepare('UPDATE kunden SET nachname = :nachname, vorname = :vorname, telefon = :telefon, email = :email WHERE id = :id');
        $updateStmt->bindParam(':nachname', $nachname);
        $updateStmt->bindParam(':vorname', $vorname);
        $updateStmt->bindParam(':telefon', $telefon);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStmt->execute();

        // Weiterleitung zur Übersicht
        header('Location: kunden.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunden bearbeiten</title>
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
        input[type="number"],
        input[type="email"] {
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
        <h1>Kunden bearbeiten</h1>
        <a href="kunden.php" class="btn-update">Zurück zur Übersicht</a>
    </div>

    <div class="form-container">
        <form method="POST">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <label for="nachname">Nachname</label>
            <input type="text" id="nachname" name="nachname" value="<?php echo htmlspecialchars($kunden['nachname']); ?>" required>

            <label for="vorname">Vorname</label>
            <input type="text" id="vorname" name="vorname" value="<?php echo htmlspecialchars($kunden['vorname']); ?>" required>

            <label for="telefon">Telefon</label>
            <input type="number" id="telefon" name="telefon" value="<?php echo htmlspecialchars($kunden['telefon']); ?>" required>

            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($kunden['email']); ?>" required>

            <button type="submit" class="btn-update">Daten aktualisieren</button>
        </form>
    </div>
</body>
</html>
