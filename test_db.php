<?php
$databaseFile = 'db/floatparade.db';

try {
    $conn = new PDO("sqlite:$databaseFile");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
