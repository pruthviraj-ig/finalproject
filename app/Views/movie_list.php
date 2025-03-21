<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">

    <!-- Header Section with Search Bar and Logout Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Movie List</h1>
        <div>
            <!-- Search Form -->
            <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search for movies..." value="<?= isset($search) ? $search : ''; ?>">
                <button type="submit" class="btn btn-outline-secondary ms-2">Search</button>
            </form>
            <?php if (session()->has('user_id')): ?>
                <a href="<?= base_url('/logout'); ?>" class="btn btn-danger mt-2">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Show API Fetched Movies -->
    <?php if (isset($apiMovies) && !empty($apiMovies)): ?>
        <h2>Search Results from OMDb API</h2>
        <div class="row">
            <?php foreach ($apiMovies as $apiMovie): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5><?= $apiMovie['Title']; ?></h5>
                            <p>Year: <?= $apiMovie['Year']; ?></p>
                            <img src="<?= $apiMovie['Poster']; ?>" class="img-fluid mb-3">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Show Local Movies -->
    <?php foreach ($movies as $movie): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5><?= $movie['title']; ?></h5>
                <p><?= $movie['description']; ?></p>
                <p>Released: <?= $movie['release_date']; ?></p>
                <?php if ($movie['poster']): ?>
                    <img src="<?= $movie['poster']; ?>" alt="Poster" class="img-fluid mb-3">
                <?php endif; ?>

                <!-- Review Form -->
                <?php if (session()->has('user_id')): ?>
                    <form class="review-form" data-movie-id="<?= $movie['id']; ?>">
                        <div class="mb-3">
                            <textarea name="review" class="form-control" placeholder="Write your review..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Rating (Out of 5):</label>
                            <input type="number" name="rating" min="1" max="5" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                    <div class="reviews"></div>
                <?php else: ?>
                    <p><a href="<?= base_url('/login'); ?>">Login to add a review</a></p>
                <?php endif; ?>

                <!-- Display Existing Reviews -->
                <h6>Reviews:</h6>
                <?php if (!empty($movie['reviews'])): ?>
                    <?php foreach ($movie['reviews'] as $review): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $review['username']; ?></strong> - Rated: <?= $review['rating']; ?>/5
                            <p><?= $review['review']; ?></p>
                            <small>Posted on: <?= $review['created_at']; ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- AJAX Handling for Review Submission -->
<script>
    $(document).ready(function(){
        $('.review-form').submit(function(e){
            e.preventDefault();
            var form = $(this);
            var movieId = form.data('movie-id');
            var formData = form.serialize();

            $.ajax({
                url: '<?= base_url('/save-review'); ?>',
                type: 'POST',
                data: formData + '&movie_id=' + movieId,
                success: function(response) {
                    if (response.success) {
                        alert('Review submitted successfully!');
                        location.reload();
                    }
                }
            });
        });
    });
</script>
</body>
</html>
