<?php

# Creation of a function that shows the rules of the game.
function show_rules() {
  echo "Welcome to the cheat card game!\n\n";
  echo "Here are the rules:\n";
  echo "1. The goal of the game is to get rid of all your cards before your opponent.\n";
  echo "2. On your turn, you can play any card from your hand.\n";
  echo "3. If you think your opponent is lying about the card they played, you can call their bluff by saying 'cheat'.\n";
  echo "4. If you successfully catch your opponent in a bluff, they have to draw two extra cards.\n";
  echo "5. If you are caught in a bluff, you have to draw one extra card.\n";
  echo "6. The game ends when one player has no cards left. The player with no cards left wins.\n\n";
  echo "Let's start the game!\n";
}


# Prerequisite Functions

function updateDatabase($db, $cards) {
    # We will first delete all the rows in the cards table
    $sql = "DELETE FROM cards";
    if (!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }

    # Adding new data to the database
    $rank = "";
    $suit = "";
    $location = "";
    $points = 0;

    $sql = "INSERT INTO cards (rank, suit, location, points) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssd", $rank, $suit, $location, $points);

    foreach ($cards as $card) {
        $rank = $card['rank'];
        $suit = $card['suit'];
        $location = $card['location'];
        $points = $card['points'];
        $stmt->execute();
    }

    $stmt->close();
}


function shuffleCards($db) {
    # We are taking the json file that contains the cards
    # We then decode it
    $json = file_get_contents('cards.json');
    $cards = json_decode($json, true);

    # We will shuffle the cards via the shuffle function
    shuffle($cards);

    # We deal the cards to the players
    # We slice the deck in two halves
    $player1Cards = array_slice($cards, 0, 26);
    $player2Cards = array_slice($cards, 26);

    # We will update the database with new card locations
    updateDatabase($db, $player1Cards);
    updateDatabase($db, $player2Cards);
    
    # We finally return the shuffled deck of cards
    return $cards;
}

# Setting player names
function setName($db, $player) {
    echo "Enter your name: ";
    $name = readline();
    $sql = "UPDATE players SET name = '$name' WHERE id = $player";
    if (!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    }
    echo "Welcome to the game, $name!\n";
}

# Dealing the cards function
function dealCards($db) {
    # We will retrieve the shuffled deck from the database
    $sql = "SELECT * FROM cards";
    $result = $db->query($sql);
    $cards = $result->fetch_all(MYSQLI_ASSOC);

    # We deal the cards while cutting them in two halves
    $player1Cards = array_slice($cards, 0, 26);
    $player2Cards = array_slice($cards, 26);

    # We need to update the database
    updateDatabase($db, $player1Cards);
    updateDatabase($db, $player2Cards);
}



#############################################




# Game Set-up
function initializeGame($db) {
    # Prior to dealing, we need to shuffle the cards
    # We use the shuffleCards function to do that
    $cards = shuffleCards($db);

    # We update everything in the database with the updateDatabase function
    updateDatabase($db, $cards);

    # Creating the tables once this function is ran
    include 'create_tables.php';

    # Asking for the player's name
    setName($db, 1);
    setName($db, 2);

    # We deal the cards
    dealCards($db);
}


function startNewGame($db) {
    # Prior to dealing, we need to shuffle the cards
    # We use the shuffleCards function to do that
    $cards = shuffleCards($db);

    # We will deal the cards to the players
    $player1Cards = array_slice($cards, 0, 26);
    $player2Cards = array_slice($cards, 26);

    # We will update the database with the latest changes
    updateDatabase($db, $player1Cards);
    updateDatabase($db, $player2Cards);
}

# Playing the Game

function playTurn($db, $playerNumber) {
    # We retrieve the player's cards from the database
    $sql = "SELECT * FROM cards WHERE location = 'player$playerNumber'";
    $result = $db->query($sql);
    $cards = $result->fetch_all(MYSQLI_ASSOC);

    # We tell the player to make a choice
    echo "Player $playerNumber, choose a card to play: \n";
    $i = 1;
    foreach ($cards as $card) {
        echo "$i: {$card['rank']} of {$card['suit']} ({$card['points']} points)\n";
        $i++;
    }

    # We get the player's choice
    $choice = readline("Enter the number of the card you want to play: ");
    $choice--; # We decrement the choice because arrays are 0-indexed

    # We save the card that the player chose
    $card = $cards[$choice];

    # We update the database to save the card that was played
    $sql = "UPDATE cards SET location = 'played' WHERE id = {$card['id']}";
    $db->query($sql);

    return $card;
}

?>
