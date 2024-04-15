<?php
// Define the path to the SQLite database file
$dbPath = "db/sqlite.db";

// Create a new database connection
$pdo = new PDO("sqlite:db/sqlite.db");

// Set error mode to exception to handle any connection errors
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Create the 'todos' table with necessary fields
    $commands = [
        'CREATE TABLE IF NOT EXISTS "todos" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "description" TEXT,
            "complete" INTEGER DEFAULT 0
        )'
    ];
    foreach ($commands as $command) {
        $pdo->exec($command);
    }
    echo "Database and table created successfully.";
} catch (PDOException $e) {
    die("Could not create table or database: " . $e->getMessage());
}
?>
