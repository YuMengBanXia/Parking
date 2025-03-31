<?php
// test_connection.php

// Incluir el archivo de configuración
require_once 'config.php';

try {
    $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, BD_USER, BD_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos " . BD_NAME . "<br>";

    // Por ejemplo, listar las tablas existentes
    $stmt = $pdo->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_NUM);
    
    if (empty($tablas)) {
        echo "No se han encontrado tablas en la base de datos.";
    } else {
        echo "Tablas en la base de datos:<br>";
        foreach ($tablas as $tabla) {
            echo "- " . $tabla[0] . "<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
