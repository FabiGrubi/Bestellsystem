<?php
// Fehlermeldungen aktivieren (zum Debuggen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbindung zur Datenbank herstellen
require dirname(__DIR__) . '/connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $bezeichnung = trim($_POST['bezeichnung']);
    $preis_netto = floatval($_POST['preis_netto']);
    $ust_satz = floatval($_POST['ust_satz']);

    // Überprüfen, ob Felder ausgefüllt sind und der Preis nicht negativ ist
    if (!empty($bezeichnung) && $preis_netto >= 0 && $ust_satz >= 0) {
        try {
            // Einfügen der Daten, id wird automatisch inkrementiert
            $stmt = $pdo->prepare("INSERT INTO artikel (id, bezeichnung, preis_netto, ust_satz) VALUES (:id, :bezeichnung, :preis_netto, :ust_satz)");
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(':bezeichnung', $bezeichnung, PDO::PARAM_STR);
            $stmt->bindValue(':preis_netto', $preis_netto, PDO::PARAM_STR);
            $stmt->bindValue(':ust_satz', $ust_satz, PDO::PARAM_STR);
            $stmt->execute();

            // Erfolgreiche Umleitung nach artikel.php
            header('Location: artikel.php?success=1');
            exit;
        } catch (PDOException $e) {
            die("Fehler beim Einfügen: " . $e->getMessage());
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Bitte alle Felder korrekt ausfüllen!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neuen Artikel hinzufügen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #6200ea;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #6200ea;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #6200ea;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #6200ea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-button:hover {
            color: #3700b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Neuen Artikel hinzufügen</h1>
        <form action="" method="POST">
            <label for="ID">ID</label> 
            <input type="text" id="id" name="id" name="id" required> 

            <label for="bezeichnung">Bezeichnung:</label>
            <input type="text" id="bezeichnung" name="bezeichnung" required>

            <label for="preis_netto">Preis_Netto:</label>
            <input type="number" id="preis_netto" name="preis_netto" step="0.01" min="0" required>

            <label for="ust_satz">Ust_Satz:</label>
            <input type="number" id="ust_satz" name="ust_satz" step="1" min="0" required>

            <button type="submit">Artikel hinzufügen</button>
        </form>
        <a href="artikel.php" class="back-button">Zurück zur Übersicht</a>
    </div>
</body>
</html>