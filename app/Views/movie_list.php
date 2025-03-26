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
        .movie-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            border: none;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .movie-card:hover {
            transform: scale(1.02);
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
        }
        .movie-poster {
            height: 300px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
        .card-body h5 {
            font-size: 1rem;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .card-body p {
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 5px;
            color: gray;
        }
        .card-body .btn {
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('/movies'); ?>">Movie Review System</a>
        <form class="d-flex ms-auto" method="get" action="<?= base_url('/movies'); ?>">
            <input class="form-control me-2" type="search" name="search" placeholder="Search movies..." aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
        <div class="ms-3">
            <?php if (session()->has('user_id')): ?>
                <a href="<?= base_url('/logout'); ?>" class="btn btn-danger">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('/login'); ?>" class="btn btn-primary">Login</a>
                <a href="<?= base_url('/register'); ?>" class="btn btn-secondary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Display API Movies -->
    <?php if (isset($apiMovies) && !empty($apiMovies)): ?>
        <h3 class="mb-4">Search Results from OMDb API</h3>
        <div class="row">
            <?php foreach ($apiMovies as $index => $apiMovie): ?>
                <div class="col-md-3 mb-4">
                    <div class="card movie-card">
                        <img src="<?= $apiMovie['Poster']; ?>" class="card-img-top movie-poster">
                        <div class="card-body">
                            <h5><?= $apiMovie['Title']; ?></h5>
                            <p>Year: <?= $apiMovie['Year']; ?></p>
                            <button class="btn btn-primary save-movie" 
                                    data-title="<?= $apiMovie['Title']; ?>"
                                    data-year="<?= $apiMovie['Year']; ?>"
                                    data-poster="<?= $apiMovie['Poster']; ?>">Save Movie</button>
                        </div>
                    </div>
                </div>
                <?php if (($index + 1) % 4 == 0): ?>
                    </div><div class="row">  <!-- Break the row after 4 movies -->
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Local Movies -->
    <div class="row">
    <?php foreach ($movies as $index => $movie): ?>
        <div class="col-md-3 mb-4">
            <div class="card movie-card">
                <div class="card-body">
                    <h5><?= $movie['title']; ?></h5>
                    <p><?= $movie['description']; ?></p>
                    <p>Released: <?= $movie['release_date']; ?></p>
                    <p>Average Rating: <?= number_format($movie['average_rating'], 1); ?> / 5 ⭐</p>

                    <?php if ($movie['poster']): ?>
                        <img src="<?= $movie['poster']; ?>" class="movie-poster mb-3">
                    <?php endif; ?>

                    <?php if (session()->has('user_id')): ?>
                        <form method="post" action="<?= base_url('/save-review'); ?>">
                            <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                            <div class="mb-3">
                                <label>Rating (Out of 5):</label>
                                <input type="number" name="rating" min="1" max="5" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <textarea name="review" class="form-control" placeholder="Write your review..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (($index + 1) % 4 == 0): ?>
            </div><div class="row">  <!-- Break the row after 4 movies -->
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.save-movie').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var title = button.data('title');
            var year = button.data('year');
            var poster = button.data('poster');

            $.ajax({
                url: '<?= base_url('/save-api-movie'); ?>',
                method: 'POST',
                data: { title: title, year: year, poster: poster },
                success: function(response) {
                    if (response.success) {
                        alert('Movie saved successfully!');
                        location.reload();
                    }
                }
            });
        });
    });
</script>

</body>
</html>
