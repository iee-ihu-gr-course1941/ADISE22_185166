<?php
require_once "db_cred.php";

$username = $DB_USERNAME;
$password = $DB_PASSWORD;
$host = $DB_HOST;
$dbname = $DB_NAME;

# Connect to the database
$db = new mysqli($host,$username,$password,$dbname);

# Error Check
if ($db->connect_errno > 0) {
    die('Connection Error: Unable to reach the database [' . $db->connect_error . ']');
}

## Game End or Restart ##

# Checking if the tables exist before deleting them
$result = $db->query("SHOW TABLES LIKE 'players'");
if ($result->num_rows > 0){
    $sql = "DROP TABLE players";
    if (!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }
}


$result = $db->query("SHOW TABLES LIKE 'games'");
if ($result->num_rows > 0){
    $sql = "DROP TABLE games";
    if (!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }
}


$result = $db->query("SHOW TABLES LIKE 'cards'");
if ($result->num_rows > 0){
    $sql = "DROP TABLE cards";
    if (!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }
}


echo "Tables have been deleted successfully!\n";

## Game Start ##

# Checks if the tables exist already in the database
$result = $db->query("SHOW TABLES LIKE 'players'");
if ($result->num_rows == 0) {
    # Creation of the players table to store player data
    $sql = "CREATE TABLE IF NOT EXISTS players (
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
    # Creation of the games table to store game data
    $sql = "CREATE TABLE IF NOT EXISTS games (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        status VARCHAR(255) NOT NULL,
        current_player INT NOT NULL,
        player_1_score INT NOT NULL,
        player_2_score INT NOT NULL
    )";

    # Error catching
    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }

    # Delete rows in the games table that have a current_player value that refers to a row in the players table
    $sql = "DELETE FROM games WHERE current_player IN (SELECT id FROM players)";
    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }
}

$result = $db->query("SHOW TABLES LIKE 'cards'");
if ($result->num_rows == 0) {
    # Creation of the cards table to store everything about the cards
    $sql = "CREATE TABLE IF NOT EXISTS cards (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        rank CHAR(2) NOT NULL,
        suit CHAR(1) NOT NULL,
        location VARCHAR(255) NOT NULL
    )";

    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }

}

echo "Tables have been created successfully!\n";


$db->close();

?>
