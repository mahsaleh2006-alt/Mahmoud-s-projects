<?php
session_start();

$_SESSION["username"] = "Mahmoud";

echo "Welcome " . $_SESSION["username"];
?>