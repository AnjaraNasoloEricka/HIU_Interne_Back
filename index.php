<?php
try {
    // Configuration avec SSL simple
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => true
    ];
    
    $pdo = new PDO(
        "mysql:host=sql.freedb.tech;dbname=freedb_hiuinterne;charset=utf8mb4", 
        "freedb_hiuinterne", 
        "kpu*kepU&p8mKM4", 
        $options
    );
    echo "Connexion réussie";
} catch (PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>