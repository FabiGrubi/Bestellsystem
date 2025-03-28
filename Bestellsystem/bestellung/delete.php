<?php
require dirname(__DIR__) . '/connect/connect.php';

// Prüfen, ob die ID über die URL übergeben wurde
if (isset($_GET['deleteID'])) {
    $id = intval($_GET['deleteID']);

    try {
        // Transaktion starten
        $pdo->beginTransaction();

        // Löschen des Bestellungs-Eintrags aus der 'bestellung' Tabelle
        $stmt = $pdo->prepare('DELETE FROM bestellung WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Transaktion abschließen
        $pdo->commit();

        // Nach dem Löschen auf die Hauptseite weiterleiten
        header("Location: bestellung.php?success=1");
        exit;
    } catch (PDOException $e) {
        // Fehlerbehandlung bei Problemen
        $pdo->rollBack();
        die("Fehler beim Löschen: " . $e->getMessage());
    }
} else {
    // Falls keine ID übergeben wurde, Weiterleitung zur Hauptseite
    header("Location: bestellung.php?error=1");
    exit;
}
?>