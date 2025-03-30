<!DOCTYPE html>
<html>
<head>
    <title><?= $movie['title']; ?> - Movie Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #141414;
            color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px #ff1e56;
        }

        .img-fluid {
            border-radius: 10px;
            box-shadow: 0 0 5px #ff1e56;
        }

        .btn-custom {
            background-color: #ff1e56;
            color: white;
            border: none;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: #ff4e00;
        }

        .form-control, textarea {
            background-color: #141414;
            color: white;
            border: 1px solid #ff1e56;
        }

        .form-control:focus, textarea:focus {
            border-color: #ff4e00;
            outline: none;
            box-shadow: none;
        }

        .review-box {
            background-color: #2a2a2a;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        h2, h3 {
            color: #ff1e56;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            color: grey;
            font-size: 12px;
        }

        iframe {
            margin-top: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <!-- Movie Poster -->
        <div class="col-md-4 text-center">
            <img src="<?= $movie['poster']; ?>" class="img-fluid mb-3" alt="Movie Poster">
        </div>

        <!-- Movie Details -->
        <div class="col-md-8">
            <h2><?= $movie['title']; ?></h2>
            <p><strong>From OMDb API</strong></p>
            <p><strong>Release Date:</strong> <?= $movie['release_date']; ?></p>
            <p><strong>Average Rating:</strong> ⭐ <?= number_format($averageRating, 1); ?> / 5</p>

            <?php if (!empty($movie['genre'])): ?><p><strong>Genre:</strong> <?= $movie['genre']; ?></p><?php endif; ?>
            <?php if (!empty($movie['director'])): ?><p><strong>Director:</strong> <?= $movie['director']; ?></p><?php endif; ?>
            <?php if (!empty($movie['actors'])): ?><p><strong>Actors:</strong> <?= $movie['actors']; ?></p><?php endif; ?>
            <?php if (!empty($movie['plot'])): ?><p><strong>Plot:</strong> <?= $movie['plot']; ?></p><?php endif; ?>
            <?php if (!empty($movie['runtime'])): ?><p><strong>Runtime:</strong> <?= $movie['runtime']; ?></p><?php endif; ?>
            <?php if (!empty($movie['language'])): ?><p><strong>Language:</strong> <?= $movie['language']; ?></p><?php endif; ?>

            <!-- Review Form -->
            <?php if (session()->has('user_id')): ?>
                <form class="review-form mt-3" method="post" action="<?= base_url('/save-review'); ?>">
                    <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                    <div class="mb-3">
                        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="review" class="form-control" placeholder="Your review..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Submit Review</button>
                </form>
            <?php else: ?>
                <p><strong>Please <a href="<?= base_url('/login'); ?>" style="color: #ff1e56;">Login</a> to leave a review.</strong></p>
            <?php endif; ?>

            <!-- ⭐ Updated Reviews Section -->
            <h3 class="mt-4">Reviews</h3>
            <div id="reviews-section">
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to add one!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-box">
                            <strong style="color: #ff1e56;"><?= $review['username']; ?>:</strong>
                            <?php
                                $rating = intval($review['rating']);
                                echo str_repeat("⭐", $rating);
                            ?>
                            <p><?= $review['review']; ?></p>
                            <?php if (session()->get('user_id') == $review['user_id']): ?>
                                <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="edit-review-form">
                                    <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                    <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                    <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                </form>
                                <button class="btn btn-danger btn-sm delete-review mt-2" data-id="<?= $review['id']; ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- 🎥 Nearby Cinemas -->
            <h3 class="mt-5" style="color: #ff1e56;">🎥 Nearby Cinemas</h3>
            <div id="cinema-results" class="mt-3 text-start" style="font-size: 0.95rem;"></div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; Pruthviraj Patil - 2310346
</footer>

<!-- JS Logic -->
<script>
    $(document).ready(function () {
        $('.review-form').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                url: '<?= base_url('/save-review'); ?>',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        alert('Review submitted successfully!');
                        location.reload();
                    } else {
                        alert('Failed to submit review.');
                    }
                },
                error: function () {
                    alert('Error submitting review.');
                }
            });
        });

        $('.delete-review').click(function () {
            var reviewId = $(this).data('id');
            if (confirm('Are you sure you want to delete this review?')) {
                $.ajax({
                    url: '<?= base_url('/delete-review/'); ?>' + reviewId,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            alert('Review deleted successfully!');
                            location.reload();
                        } else {
                            alert('Failed to delete review.');
                        }
                    },
                    error: function () {
                        alert('Error deleting review.');
                    }
                });
            }
        });
    });

    function fetchNearbyCinemas(lat, lon) {
        const query = `
            [out:json];
            (
                node["amenity"="cinema"](around:15000, ${lat}, ${lon});
            );
            out body;
        `;

        fetch("https://overpass-api.de/api/interpreter", {
            method: "POST",
            body: query,
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('cinema-results');
            if (data.elements.length === 0) {
                container.innerHTML = "<p>No cinemas found near you.</p>";
                return;
            }

            container.innerHTML = "";
            data.elements.forEach(place => {
                const name = place.tags.name || "Unnamed Cinema";
                const lat = place.lat;
                const lon = place.lon;

                const mapEmbed = `<iframe width="100%" height="200" frameborder="0" style="border:0; margin-top:10px;" 
                    src="https://maps.google.com/maps?q=${lat},${lon}&hl=en&z=16&output=embed" allowfullscreen></iframe>`;

                const div = document.createElement("div");
                div.innerHTML = `🎬 <strong>${name}</strong><br>${mapEmbed}<br><br>`;
                container.appendChild(div);
            });
        })
        .catch(() => {
            document.getElementById('cinema-results').innerHTML = "<p>Error loading cinema data.</p>";
        });
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            fetchNearbyCinemas(position.coords.latitude, position.coords.longitude);
        }, () => {
            document.getElementById('cinema-results').innerHTML = "<p>Location access denied.</p>";
        });
    } else {
        document.getElementById('cinema-results').innerHTML = "<p>Your browser does not support geolocation.</p>";
    }
</script>

</body>
</html>
