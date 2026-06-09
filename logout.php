<?php
// Starts the session so it can be accessed and modified
session_start();

// Destroys all session data (logs the user out completely)
session_destroy();

// Redirects the user back to the homepage after logout
header("Location: index.php");

// Stops further script execution to ensure redirect happens immediately
exit();
?>