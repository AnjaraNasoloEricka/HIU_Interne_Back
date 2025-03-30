<?php
try {
    $pdo = new PDO("mysql:host=sql.freedb.tech;dbname=freedb_hiuinterne", "freedb_hiuinterne", "kpu*kepU&p8mKM4", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connexion réussie";
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>