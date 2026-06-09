<?php
// Starts session to maintain user state across pages
session_start();
// Checks if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieves user input from form fields
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Specifies JSON file used to store user data
    $file = "user.json";

    // Reads existing users from file and converts JSON to PHP array
    $data = json_decode(file_get_contents($file), true);

    // If file is empty or invalid, initialise empty array
    if (! $data) {
        $data = [];
    }

    // ================== ADD NEW USER ==================
    // Appends new user credentials to array
    $data[] = [
        "username" => $username,
        "password" => $password
    ];

    // Saves updated user data back to JSON file (formatted for readability)
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    // Redirects user to login page after successful registration
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Regester</title>

<!-- Links external CSS stylesheet -->
<link rel="stylesheet" href="Style.css">
</head>
<body>

	<nav>
		<!-- Website logo/title -->
		<h1 class="logo">Luxury Living Doha</h1>

		<div>
			<!-- Navigation links -->
			<a href="index.php">Home</a> 
			<a href="ranking.php">Ranking</a> 

			<?php
// Displays logout if user is logged in, otherwise login option
if (isset($_SESSION["user"])) {
    ?>
			<a href="logout.php" class="logout">logout</a>
			<?php
} else {
    ?>
			<a href="login.php">login</a>
			<?php
}
?>

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

	<!-- Container to center the form -->
	<div class="form-container">

		<form method="POST">

			<!-- Form title -->
			<h2>Register</h2>

			<!-- Username input field -->
			<input type="text" name="username" placeholder="Mahmoud Saleh" required> 
			<br><br>

			<!-- Password input field -->
			<input type="password" name="password" placeholder="Pass123" required> 
			<br><br>

			<!-- Submit button -->
			<button type="submit">Register as a Tenant.</button>

		</form>
	</div>

	<!-- Footer section -->
	<footer class="footer">
		<p>© 2026 Luxury Living Doha | All Rights Reserved</p>
		<p>Designed by Mahmoud Saleh</p>
	</footer>

</body>

<script>
// Selects audio element and music button from DOM
let music = document.getElementById("bgMusic");
let btn = document.querySelector(".music-btn");

// Retrieves saved state from localStorage
let playing = localStorage.getItem("musicPlaying") === "true";
let currentTime = localStorage.getItem("musicTime");

// Restores playback time if it exists
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