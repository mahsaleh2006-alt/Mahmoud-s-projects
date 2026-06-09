<?php
// Starts a session to allow storing and accessing user data across pages (e.g. login state)
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<!-- Ensures the website is responsive on different screen sizes -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Title displayed on the browser tab -->
<title>Luxury Living Doha</title>

<!-- Links external CSS file for styling -->
<link rel="stylesheet" href="Style.css">
</head>
<body>

	<nav>
		<!-- Website logo/title -->
		<h1 class="logo">Luxury Living Doha</h1>

		<div>
			<!-- Navigation links -->
			<a href="ranking.php">Ranking</a> 
			
			<?php
// Checks if a user is logged in by verifying session variable
if (isset($_SESSION["user"])) {
    ?>
			<!-- Logout option shown only when user is logged in -->
			<a href="logout.php" class="logout">logout</a>
			<?php
} else {
    ?>
			<!-- Login option shown when no user is logged in -->
			<a href="login.php">login</a>
			<?php
}
?>

			<!-- Button to control background music -->
			<button onclick="toggleMusic()" class="music-btn">🎵 play Music</button>

			<!-- Audio element for background music -->
			<audio id="bgMusic">
				<source src="Music/FANCY.mp3" type="audio/mpeg">
			</audio>

			<!-- Link to overview page -->
			<a href="overview.php">Overview</a>
		</div>
	</nav>
	
	<?php
// Displays a personalized welcome message if user is logged in
if (isset($_SESSION["user"])) {
    ?>
    <div class="welcome">
    Welcome, <?php
    // Outputs the username stored in session
    echo $_SESSION["user"];
    ?> 👋
    </div>
<?php
}
?>

	<!-- Hero section introducing the website -->
	<header class="hero">
		<h2>Experience Luxury Living</h2>
		<p>Exclusive properties in Doha</p>
	</header>

	<!-- Section displaying property listings -->
	<section class="properties">

		<!-- Property card 1 -->
		<div class="card">
			<img src="Images/COUCH.webp" alt="Luxury Penthouse">
			<h3>Luxury Penthouse in Porto Arabia</h3>
			<p>4 Bed • 5 Bath • The Pearl</p>
			<p class="price">QAR 10,000,000</p>
			<!-- Passes property ID through URL to dynamically load details -->
			<a href="property.php?id=1">View Details</a>
		</div>

		<!-- Property card 2 -->
		<div class="card">
			<img src="Images/HOUSE.webp" alt="Luxury Villa">
			<h3>La Plage Luxury Villa</h3>
			<p>5 Bed • 7 Bath • The Pearl</p>
			<p class="price">QAR 60,000,000</p>
			<a href="property.php?id=2">View Details</a>
		</div>

		<!-- Property card 3 -->
		<div class="card">
			<img src="Images/BIG.webp" alt="Luxury Apartment">
			<h3>Giardino Apartment</h3>
			<p>7 Bed • 7 Bath • The Pearl</p>
			<p class="price">QAR 55,000,000</p>
			<a href="property.php?id=3">View Details</a>
		</div>
	</section>

	<!-- Footer section -->
	<footer class="footer">
		<p>© 2026 Luxury Living Doha | All Rights Reserved</p>
		<p>Designed by Mahmoud Saleh</p>
	</footer>

</body>

<script>
// Selects the audio element and button from the DOM
let music = document.getElementById("bgMusic");
let btn = document.querySelector(".music-btn");

// Retrieves saved music state from localStorage (persists across page reloads)
let playing = localStorage.getItem("musicPlaying") === "true";
let currentTime = localStorage.getItem("musicTime");

// Restores the last saved playback time if available
if (currentTime) {
    music.currentTime = currentTime;
}

// Restores playing state (auto-plays music if it was previously playing)
if (playing) {
    music.play();
    btn.innerText = "⏸️ Pause Music";
}

// Function to toggle music play/pause
function toggleMusic() {
    if (!playing) {
        // Play music and update button text
        music.play();
        btn.innerText = "⏸️ Pause Music";
        playing = true;

        // Save playing state in localStorage
        localStorage.setItem("musicPlaying", "true");
    } else {
        // Pause music and update button text
        music.pause();
        btn.innerText = "🎵 Play Music";
        playing = false;

        // Save paused state in localStorage
        localStorage.setItem("musicPlaying", "false");
    }
}

// Continuously saves the current playback time while music is playing
music.addEventListener("timeupdate", () => {
    localStorage.setItem("musicTime", music.currentTime);
});
</script>

</html>