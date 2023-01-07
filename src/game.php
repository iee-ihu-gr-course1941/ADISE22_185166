<?php

# Authentication Link ------ REMOVE COMMENT BEFORE SUBMITTING (dean)
# require_once 'db_cred.php';

# Authentication Items ------ REMOVE COMMENT BEFORE SUBMITTING (dean)
# $username = $DB_USERNAME;
# $password = $DB_PASSWORD;
# $host = $DB_HOST;
#$dbname = $DB_NAME;

# Include libraries
include 'functions.php';

# Initalizing MySQLi connection ------ PUT THE CORRECT ARGUMENTS BEFORE SUBMITTING (dean)
# Connect to the database
$db = new mysqli("localhost", "root", "password", "test");

# Error Check
if ($db->connect_errno > 0){
    die('Connection Error: Unable to reach the database [' . $db->connect_error . ']');



}
