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
    $sql = "INSERT INTO cards (rank, suit, location) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $rank, $suit, $location);

    foreach ($cards as $card) {
        $rank = $card['rank'];
        $suit = $card['suit'];
        $location = $card['location'];
        $stmt->execute();
    }

    $stmt->close();
}




function shuffleCards() {
    # We are taking the json file that contains the cards
    # We then decode it
    $json = file_get_contents('cards.json');
    $cards = json_decode($json, true);

    # We will shuffle the cards via the shuffle function
    shuffle($cards);

    # We finally return the shuffled deck of cards
    return $cards;
}






#############################################





# Game initialization
function initializeGame() {
    # Prior to dealing, we need to shuffle the cards
    # We use the shuffleCards function to do that
    $deck = shuffleCards();

    # We deal the cards to the players
    # We slice the deck in two halves
    $player1Cards = array_slice($deck, 0, 26);
    $player2Cards = array_slice($deck, 26);

    # We update everything in the database with the updateDatabase function
    updateDatabase($player1Cards, $player2Cards);


}


?>
