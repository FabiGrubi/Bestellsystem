<?php
require dirname(__DIR__) . '/connect/connect.php';

// Alle Datensätze anzeigen
$stmt = $pdo->prepare('
   SELECT `bestellung`.`id`, `kunden`.`vorname`, `kunden`.`nachname`, `bestellung`.`datum`
    FROM `bestellung`
    LEFT JOIN `kunden` ON `bestellung`.`id_kunde` = `kunden`.`id`
    ORDER BY `bestellung`.`datum` ASC;

');
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellungen Übersicht</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color:rgb(245, 245, 245);
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color:rgb(3, 24, 250);
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.49);
            border-radius: 8px;
            overflow: hidden;
        }

        table thead {
            background-color:rgb(234, 0, 0);
            color: #fff;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px rgb(224, 224, 224);
        }

        table tbody tr:nth-child(even) {
            background-color:rgb(249, 249, 249);
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background-color:rgb(20, 0, 234);
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color:rgb(179, 0, 0);
        }

        .action-btn {
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .btn-update {
            background-color:rgb(50, 30, 233);
        }

        .btn-update:hover {
            background-color:rgb(50, 30, 233);
        }

        .btn-delete {
            background-color:rgb(244, 3, 3);
        }

        .btn-delete:hover {
            background-color:rgb(244, 3, 3);
        }

        .add-btn-container {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Bestell Übersicht</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Datum</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['id']); ?></td>
                    <td><?php echo htmlspecialchars($result['vorname']); ?></td>
                    <td><?php echo htmlspecialchars($result['nachname']); ?></td>
                    <td><?php echo htmlspecialchars($result['datum']); ?></td>
                    <td><a href="update.php?id=<?php echo $result['id']; ?>" class="action-btn btn-update">Update</a></td>
                    <td><a href="delete.php?deleteID=<?php echo $result['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Möchten Sie diesen Eintrag wirklich löschen?');">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="add-btn-container">
        <a href="insert.php" class="btn">Neue Bestellung hinzufügen</a>
    </div>
</body>
</html>