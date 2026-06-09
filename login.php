<?php
// Starts session to allow user login state to persist across pages
session_start();
// Variable to store error messages (e.g. invalid login)
$error = "";

// Checks if the form has been submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieves user input from form fields
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Specifies JSON file where user data is stored
    $file = "user.json";

    // Reads file and converts JSON data into PHP array
    $users = json_decode(file_get_contents($file), true);

    $found = false; // Flag to track if user credentials match

    // Checks if user data exists
    if ($users) {

        // Loops through each user in the JSON file
        foreach ($users as $user) {

            // Compares entered username and password with stored values
            if ($user["username"] == $username && $user["password"] == $password) {
                $found = true; // Match found
                break; // Exit loop early for efficiency
            }
        }
    }

    // If valid credentials were found
    if ($found) {

        // Store username in session to keep user logged in
        $_SESSION["user"] = $username;

        // Redirect user to homepage after successful login
        header("Location: index.php");
        exit();
    } else {

        // Display error message if login fails
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<!-- Links external CSS file for styling -->
<link rel="stylesheet" href="Style.css">
</head>
<body>

	<nav>
		<!-- Website logo/title -->
		<h1 class="logo">Luxury Living Doha</h1>

		<div>
			<!-- Navigation links -->
			<a href="index.php">Home</a> <a href="ranking.php">Ranking</a>


			<!-- Music control button -->
			<button onclick="toggleMusic()" class="music-btn">🎵 play Music</button>

			<!-- Background music element -->
			<audio id="bgMusic">
				<source src="Music/FANCY.mp3" type="audio/mpeg">
			</audio>

			<!-- Link to overview page -->
			<a href="overview.php">Overview</a>
		</div>
	</nav>

	<!-- Container to center the login form vertically and horizontally -->
	<div class="form-container">

		<form method="POST">

			<!-- Form title -->
			<h2>Login</h2>

			<!-- Username input field -->
			<input type="text" name="username" placeholder="Amro" required> <br>
			<br>

			<!-- Password input field -->
			<input type="password" name="password" placeholder="Pass123" required>
			<br> <br>

			<!-- Submit button -->
			<button type="submit">Login</button>

			<!-- NEW: Continue as Guest -->
			<br> <br> <a href="index.php" class="guest-btn">continue as Guest</a>

			<!-- Displays error message if login fails -->
			<p style="color: red;"><?php

echo $error;
?></p>

			<!-- Link to registration page -->
			<p>
				Not a Tenant? <a href="Regester.php">Regester</a>
			</p>

		</form>
	</div>

	<!-- Footer section -->
	<footer class="footer">
		<p>© 2026 Luxury Living Doha | All Rights Reserved</p>
		<p>Designed by Mahmoud Saleh</p>
	</footer>

</body>

<script>
// Selects audio element and music button
let music = document.getElementById("bgMusic");
let btn = document.querySelector(".music-btn");

// Retrieves saved playback state from localStorage
let playing = localStorage.getItem("musicPlaying") === "true";
let currentTime = localStorage.getItem("musicTime");

// Restores previous playback time if available
if (currentTime) {
    music.currentTime = currentTime;
}

// Restores play/pause state
if (playing) {
    music.play();
    btn.innerText = "⏸️ Pause Music";
}

// Function to toggle music playback
function toggleMusic() {
    if (!playing) {
        music.play();
        btn.innerText = "⏸️ Pause Music";
        playing = true;

        // Save playing state
        localStorage.setItem("musicPlaying", "true");
    } else {
        music.pause();
        btn.innerText = "🎵 Play Music";
        playing = false;

        // Save paused state
        localStorage.setItem("musicPlaying", "false");
    }
}

// Continuously saves current playback time
music.addEventListener("timeupdate", () => {
    localStorage.setItem("musicTime", music.currentTime);
});
</script>

</html>