
<?php
    // PDO - PHP Data Object

    define('DATABASE_SERVER', 'localhost');
    define('DATABASE_USER', 'root');
    define('DATABASE_PASSWORD', '');
    define('DATABASE_NAME', 'tech_store');

    $connection = null;

    try {
        $connection = new PDO(
            "mysql:host=" . DATABASE_SERVER .
            ";dbname=" . DATABASE_NAME .
            ";charset=utf8mb4",
            DATABASE_USER,
            DATABASE_PASSWORD
        );

        $connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $connection->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
