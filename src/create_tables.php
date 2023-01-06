<?php

// Connect to the database
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Error Check
if ($db->connect_errno > 0) {
    die('Connection Error: Unable to reach the database [' . $db->connect_error . ']');
}

// Checks if the tables exist already in the database
$result = $db->query("SHOW TABLES LIKE 'players'");
if ($result->num_rows == 0) {
    // Creation of the players table to store player data
    $sql = "CREATE TABLE players (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        hand TEXT NOT NULL
    )";

    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }
}

$result = $db->query("SHOW TABLES LIKE 'games'");
if ($result->num_rows == 0) {
    // Creation of the games table to store game data
    $sql = "CREATE TABLE games (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        status VARCHAR(255) NOT NULL,
        current_player INT NOT NULL,
        player_1_score INT NOT NULL,
        player_2_score INT NOT NULL,
        FOREIGN KEY (current_player) REFERENCES players(id)
    )";

    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }
}

$result = $db->query("SHOW TABLES LIKE 'cards'");
if ($result->num_rows == 0) {
    // Creation of the cards table to store everything about the cards
    $sql = "CREATE TABLE cards (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        rank CHAR(2) NOT NULL,
        suit CHAR(1) NOT NULL,
        location VARCHAR(255) NOT NULL
    )";

    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }
}

echo "Tables have been created successfully!";

// Every table is deleted when the game ends or closes
$sql = "DROP TABLE players, games, cards";
if (!$result = $db->query($sql)) {
    die('There was an error running the query [' . $db->error . ']');
}

echo "Tables have been deleted successfully!";

$db->close();

?>
