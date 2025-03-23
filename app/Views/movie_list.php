<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .star {
            font-size: 24px;
            color: gold;
        }
        .average-rating {
            font-size: 20px;
            color: #555;
            margin-bottom: 10px;
        }
        .movie-poster {
            height: 400px;
            object-fit: cover;
            border-radius: 5px;
        }
        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 24px;
                text-align: center;
            }
            .card {
                margin-bottom: 15px;
            }
            .btn, .form-control {
                width: 100%;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <!-- Header with Search Bar and User Status -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Movie List</h1>
        
        <!-- Search Form -->
        <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= isset($search) ? $search : ''; ?>">
            <button type="submit" class="btn btn-outline-secondary ms-2">Search</button>
        </form>
        
        <!-- User Status -->
        <div>
            <?php if (session()->has('user_id')): ?>
                <a href="<?= base_url('/logout'); ?>" class="btn btn-danger ms-2">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('/login'); ?>" class="btn btn-primary ms-2">Login</a>
                <a href="<?= base_url('/register'); ?>" class="btn btn-secondary ms-2">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Display API Movies -->
    <?php if (isset($apiMovies) && !empty($apiMovies)): ?>
        <h3>Search Results from OMDb API</h3>
        <div class="row">
            <?php foreach ($apiMovies as $apiMovie): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= $apiMovie['Poster'] ?>" class="card-img-top movie-poster" alt="Movie Poster">
                        <div class="card-body">
                            <h5 class="card-title"><?= $apiMovie['Title'] ?></h5>
                            <p class="card-text">Year: <?= $apiMovie['Year'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Local Movies -->
    <div class="row">
        <?php foreach ($movies as $movie): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5><?= $movie['title']; ?></h5>
                        <p><?= $movie['description']; ?></p>
                        <p>Released: <?= $movie['release_date']; ?></p>
                        <p class="average-rating">Average Rating: <?= number_format($movie['average_rating'], 1); ?> / 5 ⭐</p>

                        <?php if ($movie['poster']): ?>
                            <img src="<?= $movie['poster']; ?>" class="img-fluid mb-3">
                        <?php endif; ?>

                        <!-- Review Form -->
                        <?php if (session()->has('user_id')): ?>
                            <form class="review-form" data-movie-id="<?= $movie['id']; ?>">
                                <div class="mb-3">
                                    <label>Rating (Out of 5):</label>
                                    <input type="number" name="rating" min="1" max="5" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="review" class="form-control" placeholder="Write your review..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        <?php else: ?>
                            <p><a href="<?= base_url('/login'); ?>">Login to add a review</a></p>
                        <?php endif; ?>

                        <!-- Display Reviews -->
                        <h6>Reviews:</h6>
                        <?php foreach ($movie['reviews'] as $review): ?>
                            <div class="border p-2 mb-2">
                                <strong><?= $review['username']; ?></strong> - Rated: <?= $review['rating']; ?>/5 ⭐
                                <p><?= $review['review']; ?></p>
                                <small>Posted on: <?= $review['created_at']; ?></small>

                                <?php if (session()->get('user_id') == $review['user_id']): ?>
                                    <!-- Edit Review Form -->
                                    <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="d-inline">
                                        <div class="mb-1 mt-2">
                                            <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                            <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm mb-2">Edit</button>
                                    </form>
                                    <a href="<?= base_url('/delete-review/' . $review['id']); ?>" class="btn btn-danger btn-sm mb-2">Delete</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
