<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
        }
        .movie-poster {
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .average-rating {
            color: #ff9800;
            margin-bottom: 5px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }
        .btn-save {
            margin-top: 5px;
            background-color: #007bff;
            color: white;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎬 Movie List</h1>
        <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= isset($search) ? $search : ''; ?>">
            <button type="submit" class="btn btn-outline-secondary ms-2">Search</button>
        </form>
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
        <h3>🔍 Search Results from OMDb API</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            <?php foreach ($apiMovies as $apiMovie): ?>
                <div class="col">
                    <div class="card p-2">
                        <img src="<?= $apiMovie['Poster'] ?>" class="card-img-top movie-poster" alt="Movie Poster">
                        <div class="card-body text-center">
                            <h6><?= $apiMovie['Title'] ?></h6>
                            <p>Year: <?= $apiMovie['Year'] ?></p>
                            <button class="btn btn-save save-movie"
                                    data-title="<?= $apiMovie['Title'] ?>"
                                    data-description="Fetched from OMDb API"
                                    data-release_date="<?= $apiMovie['Year'] ?>-01-01"
                                    data-poster="<?= $apiMovie['Poster'] ?>">
                                Save to Local Database
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Local Movies -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 mt-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col">
                <div class="card p-2">
                    <div class="card-body text-center">
                        <h6><?= $movie['title']; ?></h6>
                        <p class="average-rating">⭐ <?= number_format($movie['average_rating'], 1); ?> / 5</p>
                        
                        <?php if ($movie['poster']): ?>
                            <img src="<?= $movie['poster']; ?>" class="movie-poster mb-2">
                        <?php endif; ?>

                        <?php if (session()->has('user_id')): ?>
                            <form class="review-form mt-2" data-movie-id="<?= $movie['id']; ?>">
                                <input type="number" name="rating" min="1" max="5" class="form-control mb-1" placeholder="Rating (1-5)" required>
                                <textarea name="review" class="form-control mb-1" placeholder="Your review..." required></textarea>
                                <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                            </form>
                        <?php endif; ?>

                        <div class="mt-2">
                            <?php foreach ($movie['reviews'] as $review): ?>
                                <div class="border p-1 mb-1">
                                    <strong><?= $review['username']; ?>:</strong> <?= $review['rating']; ?>/5 ⭐
                                    <p><?= $review['review']; ?></p>
                                    <?php if (session()->get('user_id') == $review['user_id']): ?>
                                        <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="d-inline">
                                            <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                            <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                        </form>
                                        <a href="<?= base_url('/delete-review/' . $review['id']); ?>" class="btn btn-danger btn-sm mb-1">Delete</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- AJAX Handling -->
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

        $('.save-movie').click(function(){
            var button = $(this);
            var movieData = {
                title: button.data('title'),
                description: button.data('description'),
                release_date: button.data('release_date'),
                poster: button.data('poster')
            };

            $.ajax({
                url: '<?= base_url('/save-movie'); ?>',
                type: 'POST',
                data: movieData,
                success: function(response) {
                    if (response.success) {
                        alert('Movie saved successfully!');
                        button.text('Saved').prop('disabled', true);
                    }
                }
            });
        });
    });
</script>
</body>
</html>
