<?php
// Starts session to track user login state across pages
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Overview</title>

<!-- Links external CSS file for styling -->
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
            <a href="logout.php" class="logout">Logout</a>
        <?php
        } else {
            ?>
            <a href="login.php">Login</a>
        <?php
        }
        ?>

        <!-- Music control button -->
        <button onclick="toggleMusic()" class="music-btn">🎵 Play Music</button>

			<!-- Background music element -->
			<audio id="bgMusic">
				<source src="Music/FANCY.mp3" type="audio/mpeg">
			</audio>
		</div>
	</nav>

	<!-- Page title -->
	<h1 class="overview-title">Property Listing Service Overview</h1>

	<!-- Main container holding content and image -->
	<div class="overview-container">

		<!-- LEFT SIDE: TEXT CONTENT -->
		<!-- Uses 'card' class for white box styling -->
		<div class="overview-left card">

			<!-- Section title -->
			<h2>About PropertyFinder</h2>

			<!-- Description paragraph -->
			<p>
				PropertyFinder is a leading real estate platform that allows users
				to search for apartments, villas, and luxury properties across
				different locations. It provides detailed listings, high-quality
				images, and advanced filtering tools to help users find their ideal
				home.
			</p>

			<!-- Feature list -->
			<h3>Key Features</h3>
			<ul>
				<li>Advanced property search filters</li>
				<li>Location-based browsing</li>
				<li>High-quality images and virtual tours</li>
				<li>User ratings and reviews</li>
			</ul>

			<!-- Platform availability -->
			<h3>Platforms</h3>
			<p>
				The platform is available on web browsers and mobile applications,
				allowing users to access property listings anytime and anywhere.
			</p>

		</div>

		<!-- RIGHT SIDE: IMAGE -->
		<div class="overview-right">

			<!-- Displays property-related image -->
			<img src="Images/HOUSE.webp" class="overview-img">

		</div>

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

// Restores previous playback time
if (currentTime) music.currentTime = currentTime;

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

// Continuously saves playback time
music.addEventListener("timeupdate", () => {
    localStorage.setItem("musicTime", music.currentTime);
});
</script>

</html>