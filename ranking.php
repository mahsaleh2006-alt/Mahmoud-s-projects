<?php
// Starts session to maintain user login state across pages
session_start();
// ================== PROPERTY DATA ==================
// Defines an array of properties with base scores and images
$properties = [
    [
        "id" => 1,
        "name" => "Luxury Penthouse in Porto Arabia",
        "base_score" => 6, // Default score if no user reviews exist
        "Image" => "Images/COUCH.webp"
    ],
    [
        "id" => 2,
        "name" => "La Plage Luxury Villa",
        "base_score" => 8.5,
        "Image" => "Images/HOUSE.webp"
    ],
    [
        "id" => 3,
        "name" => "Giardino Apartment",
        "base_score" => 9.5,
        "Image" => "Images/BIG.webp"
    ]
];

// ================== LOAD JSON REVIEWS ==================
// Reads reviews from JSON file and converts into PHP array
$file = "reviews.json";
$data = json_decode(file_get_contents($file), true);

// ================== CALCULATE SCORES ==================
// Loops through each property to calculate its dynamic score
foreach ($properties as $index => $p) {

    $id = $p["id"];
    $total = 0; // Sum of ratings
    $count = 0; // Number of reviews

    // Checks if review data exists
    if ($data) {

        // Loops through all reviews
        foreach ($data as $r) {

            // Filters reviews that belong to current property
            if ((int) $r["property_id"] == (int) $id) {
                $total += (int) $r["rating"]; // Add rating
                $count ++; // Increase count
            }
        }
    }

    // Calculates average score if reviews exist
    if ($count > 0) {
        $properties[$index]["score"] = round($total / $count, 1);
    } else {
        // Otherwise, fallback to predefined base score
        $properties[$index]["score"] = $p["base_score"];
    }
}

// ================== SORT PROPERTIES ==================
// Sorts properties in descending order based on score
usort($properties, function ($a, $b) {
    return $b["score"] <=> $a["score"]; // Spaceship operator for comparison
});
?>

<!DOCTYPE html>
<html>
<head>
<title>Ranking</title>

<!-- Links external CSS stylesheet -->
<link rel="stylesheet" href="Style.css">
</head>
<body>

	<nav>
		<!-- Website title -->
		<h1 class="logo">Luxury Living Doha</h1>

		<div>
			<!-- Navigation links -->
			<a href="index.php">Home</a> 
			

			<?php
// Shows logout if user is logged in, otherwise login option
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

			<!-- Background music -->
			<audio id="bgMusic">
				<source src="Music/FANCY.mp3" type="audio/mpeg">
			</audio>

			<a href="overview.php">Overview</a>
		</div>
	</nav>

	<!-- Page title -->
	<h2 style="text-align: center; margin-top: 30px;">Property Ranking</h2>

	<!-- Container for property cards -->
	<div class="properties">

<?php
// Loops through sorted properties and displays them
foreach ($properties as $index => $p) {
    ?>

<div class="card">

			<!-- Property image -->
			<img src="<?php

    echo $p["Image"];
    ?>"
				style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px;">

			<!-- Property name -->
			<h3><?php

    echo $p["name"];
    ?></h3>

    <?php
    // Highlights the top-ranked property
    if ($index == 0) {
        ?>
        <p style="color: gold; font-weight: bold;">🏅 Top Rated</p>
    <?php
    }
    ?>

    <!-- Display calculated score -->
    <p class="price">
        Score: <?php

    echo $p["score"];
    ?>/10
    </p>

		</div>

<?php
}
?>

</div>

	<!-- Footer -->
	<footer class="footer">
		<p>© 2026 Luxury Living Doha | All Rights Reserved</p>
		<p>Designed by Mahmoud Saleh</p>
	</footer>

</body>

<script>
// Selects audio element and button
let music = document.getElementById("bgMusic");
let btn = document.querySelector(".music-btn");

// Retrieves saved playback state
let playing = localStorage.getItem("musicPlaying") === "true";
let currentTime = localStorage.getItem("musicTime");

// Restores playback position
if (currentTime) {
    music.currentTime = currentTime;
}

// Restores play state
if (playing) {
    music.play();
    btn.innerText = "⏸️ Pause Music";
}

// Toggles music play/pause
function toggleMusic() {
    if (!playing) {
        music.play();
        btn.innerText = "⏸️ Pause Music";
        playing = true;
        localStorage.setItem("musicPlaying", "true");
    } else {
        music.pause();
        btn.innerText = "🎵 Play Music";
        playing = false;
        localStorage.setItem("musicPlaying", "false");
    }
}

// Continuously saves playback time
music.addEventListener("timeupdate", () => {
    localStorage.setItem("musicTime", music.currentTime);
});
</script>

</html>