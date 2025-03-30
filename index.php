<?php
try {
    // Configuration avec SSL simple
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 30,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => false
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