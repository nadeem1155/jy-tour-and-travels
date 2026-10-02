<?php
/**
 * JY TOUR and TRAVELS - Database Seed Script
 * Can be run from command line: php database/seed_data.php
 */
require_once __DIR__ . '/../config/database.php';

echo "JY TOUR and TRAVELS - Database Seeder\n";
echo "=====================================\n";

$pdo = get_db_connection();
if (!$pdo) {
    die("Error: Could not establish database connection. Please check config/database.php credentials.\n");
}

$schemaFile = __DIR__ . '/schema.sql';
if (!file_exists($schemaFile)) {
    die("Error: schema.sql file not found.\n");
}

try {
    $sql = file_get_contents($schemaFile);
    $pdo->exec($sql);
    echo "Success: Database schema and seed data imported successfully!\n";
    echo "Default Admin: admin / Admin@JY2026#Secure\n";
} catch (Exception $e) {
    die("Database seeding error: " . $e->getMessage() . "\n");
}
