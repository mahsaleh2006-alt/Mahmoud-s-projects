<?php
// Starts session to track user login state across pages
session_start();

// Gets property ID from URL (e.g. property.php?id=1)
$id = $_GET['id'];

// ================== HANDLE REVIEW ACTIONS ==================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user"])) {

    $file = "reviews.json";

    // Reads existing reviews from JSON file
    $data = json_decode(file_get_contents($file), true);

    // If file is empty, initialise empty array
    if (! $data) {
        $data = [];
    }

    // ================== ADD REVIEW ==================
    if (isset($_POST["newReview"]) && isset($_POST["rating"])) {

        // Add new review to array
        $data[] = [
            "property_id" => (int) $id,
            "user" => $_SESSION["user"],
            "text" => $_POST["newReview"],
            "rating" => (int) $_POST["rating"]
        ];
    }

    // ================== DELETE REVIEW ==================
    if (isset($_POST["delete_index"])) {
        $deleteIndex = $_POST["delete_index"];

        // Check if review exists
        if (isset($data[$deleteIndex])) {

            // Only allow deletion if current user owns the review
            if ($data[$deleteIndex]["user"] == $_SESSION["user"]) {

                unset($data[$deleteIndex]); // Remove review

                // Re-index array after deletion
                $data = array_values($data);
            }
        }
    }

    // Save updated data back to JSON file
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    // Redirect to avoid form resubmission issue
    header("Location: property.php?id=" . $id);
    exit();
}

// ================== PROPERTY DATA ==================
if ($id == 1) {
    $name = "Luxury Penthouse in Porto Arabia";
    $price = "QAR 10,000,000";
    $beds = "4 Bed • 5 Bath • The Pearl";

    // Images used in slider
    $images = [
        "Images/COUCH.webp",
        "Images/10M 1.webp",
        "Images/10M 2.webp",
        "Images/10M 3.webp",
        "Images/10M 4.webp",
        "Images/10M 5.webp"
    ];

    // Default reviews
    $reviews = [
        "Prime location but the overall finish feels underwhelming for the price.",
        "Good views, however the interior design lacks a modern touch.",
        "Decent property but there are better options available in the same range."
    ];

    // External reviews (simulated third-party sources)
    $external = [
        "Source: PropertyFinder | Rating: 6.2/10 — Decent property but lacks modern finishing.",
        "Source: Zillow | Rating: 7.1/10 — Spacious layout with great views, though some areas feel under-maintained.",
        "Source: Bayut | Rating: 5.8/10 — Overpriced compared to similar properties, but still decent overall."
    ];
} elseif ($id == 2) {

    $name = "La Plage Luxury Villa";
    $price = "QAR 60,000,000";
    $beds = "5 Bed • 7 Bath • The Pearl";

    $images = [
        "Images/HOUSE.webp",
        "Images/60M 1.webp",
        "Images/60M 2.webp",
        "Images/60M 3.webp",
        "Images/60M 4.webp",
        "Images/60M 5.webp"
    ];

    $reviews = [
        "Beautifully designed villa with excellent space and privacy.",
        "High-end property with great features, though slightly overpriced.",
        "Very comfortable living space with strong overall quality."
    ];

    $external = [
        "Source: PropertyFinder | Rating: 8.7/10 — High-quality villa with excellent space and design.",
        "Source: Zillow | Rating: 7.9/10 — Beautiful design but pricing feels slightly inflated for the current market.",
        "Source: Bayut | Rating: 8.4/10 — Strong property with great space, though location may not suit everyone."
    ];
} elseif ($id == 3) {

    $name = "Giardino Apartment";
    $price = "QAR 55,000,000";
    $beds = "7 Bed • 7 Bath • The Pearl";

    $images = [
        "Images/BIG.webp",
        "Images/55M 1.webp",
        "Images/55M 2.webp",
        "Images/55M 3.webp",
        "Images/55M 4.webp",
        "Images/55M 5.webp"
    ];

    $reviews = [
        "Outstanding design and premium finishes throughout the property.",
        "Exceptional living experience with top-tier amenities and location.",
        "One of the best properties available, combining luxury and comfort perfectly."
    ];

    $external = [
        "Source: PropertyFinder | Rating: 9.4/10 — Exceptional property with premium features and location.",
        "Source: Zillow | Rating: 8.8/10 — Luxury finish and great design, but slightly overpriced in comparison to alternatives.",
        "Source: Bayut | Rating: 9.1/10 — One of the best properties available, offering comfort, space, and high-end amenities."
    ];
}

// ================== CALCULATE SCORE ==================
$total = 0;
$count = 0;

$file = "reviews.json";
$data = json_decode(file_get_contents($file), true);

$base_scores = [
    1 => 6,
    2 => 8.5,
    3 => 9.5
];

$score = $base_scores[$id];

// Loop through JSON reviews to calculate average rating
if ($data) {
    foreach ($data as $r) {
        if ((int) $r["property_id"] == (int) $id) {
            $total += (int) $r["rating"];
            $count ++;
        }
    }
}

// Override score if user reviews exist
if ($count > 0) {
    $score = round($total / $count, 1);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Property Details</title>

<!-- Link to stylesheet (versioning used to prevent caching issues) -->
<link rel="stylesheet" href="style.css?v=2">
</head>
<body>

	<nav>
		<h1 class="logo">Luxury Living Doha</h1>

		<div>
			<!-- Navigation links -->
			<a href="index.php">Home</a> <a href="ranking.php">Ranking</a> 

			<?php
// Show logout if logged in, otherwise show login
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

	<!-- Property title -->
	<h2 class="property-title"><?php

echo $name;
?></h2>

	<div class="property-container">

		<!-- LEFT: IMAGE SLIDER -->
		<div class="property-left">
			<div class="image-wrapper">

				<!-- Main image -->
				<img src="<?php

    echo $images[0];
    ?>" id="mainImage"
					class="main-img" onclick="openFullscreen()">

				<!-- Navigation arrows -->
				<button class="nav left" onclick="prevImage()">❮</button>
				<button class="nav right" onclick="nextImage()">❯</button>
			</div>
		</div>

		<!-- RIGHT: PROPERTY DETAILS -->
		<div class="property-right property-box">

			<p><?php

echo $beds;
?></p>
			<p class="price"><?php

echo $price;
?></p>

			<!-- Dynamic rating display -->
			<p class="score">⭐ Rating: <?php

echo $score;
?>/10 (<?php

echo $count;
?> reviews)</p>

			<!-- REVIEWS SECTION -->
			<div class="reviews-box">
				<h3>Tenant Reviews</h3>

				<ul>
                <?php
                // Display default reviews
                foreach ($reviews as $review) {
                    echo "<li>$review</li>";
                }

                // Display user-submitted reviews
                if ($data) {
                    foreach ($data as $index => $r) {

                        if ($r["property_id"] == $id) {

                            echo "<li class='review'>";

                            echo "<span class='review-text'>" . $r["user"] . ": " . $r["text"] . " (⭐ " . $r["rating"] . "/10)" . "</span>";

                            if (isset($_SESSION["user"]) && $_SESSION["user"] == $r["user"]) {
                                echo "
    <form method='POST' class='delete-form'>
        <input type='hidden' name='delete_index' value='$index'>
        <button type='submit' class='delete-btn'>✖</button>
    </form>
    ";
                            }

                            echo "</li>";
                        }
                    }
                }
                ?>
            </ul>
			</div>

			<!-- External reviews -->
			<h3>External Review</h3>
			<?php
foreach ($external as $review) {
    echo "<p class='external'>$review</p>";
}
?>

        <?php
        // Show review form only if user is logged in
        if (isset($_SESSION["user"])) {
            ?>
            <h3>Add Your Review</h3>

			<form method="POST">
				<input type="text" name="newReview"
					placeholder="Write your review..." required><br>
				<br> <label>Rating (1–10):</label> <input type="number"
					name="rating" min="1" max="10" required><br>
				<br>

				<button type="submit">Submit</button>
			</form>

        <?php
        } else {
            ?>
            <p>
				Please <a href="login.php">login</a> to add a review.
			</p>
        <?php
        }
        ?>

    </div>
	</div>

	<!-- FULLSCREEN IMAGE VIEWER -->
	<div id="fullscreenViewer" class="fullscreen">
		<span class="close-btn" onclick="closeFullscreen()">✖</span> <img
			id="fullscreenImage" class="fullscreen-img">

		<button class="nav left" onclick="prevImage()">❮</button>
		<button class="nav right" onclick="nextImage()">❯</button>
	</div>

	<script>
	// Image slider logic
	let images = <?php

echo json_encode($images);
?>;
	let index = 0;

	function showImage() {
	    document.getElementById("mainImage").src = images[index];
	    let full = document.getElementById("fullscreenImage");
	    if (full) full.src = images[index];
	}

	function nextImage() {
	    index++;
	    if (index >= images.length) index = 0;
	    showImage();
	}

	function prevImage() {
	    index--;
	    if (index < 0) index = images.length - 1;
	    showImage();
	}

	function openFullscreen() {
	    document.getElementById("fullscreenViewer").style.display = "flex";
	    document.getElementById("fullscreenImage").src = images[index];
	    document.body.style.overflow = "hidden"; // Prevent scrolling
	}

	function closeFullscreen() {
	    document.getElementById("fullscreenViewer").style.display = "none";
	    document.body.style.overflow = "auto";
	}
	</script>

	<script>
	// Music persistence system
	let music = document.getElementById("bgMusic");
	let btn = document.querySelector(".music-btn");

	let playing = localStorage.getItem("musicPlaying") === "true";
	let currentTime = localStorage.getItem("musicTime");

	// Restore playback position
	if (currentTime) music.currentTime = currentTime;

	// Restore play state
	if (playing) {
	    music.play();
	    btn.innerText = "⏸️ Pause Music";
	}

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

	// Continuously save playback time
	music.addEventListener("timeupdate", () => {
	    localStorage.setItem("musicTime", music.currentTime);
	});
	</script>

	<footer class="footer">
		<p>© 2026 Luxury Living Doha | All Rights Reserved</p>
		<p>Designed by Mahmoud Saleh</p>
	</footer>

</body>
</html>