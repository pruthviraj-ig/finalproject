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
            width: 100%;
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
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎬 Movie List</h1>
        
        <div class="d-flex align-items-center">
            <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex me-3">
                <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= isset($search) ? $search : ''; ?>">
                <button type="submit" class="btn btn-outline-secondary ms-2">Search</button>
            </form>
            
            <?php if (session()->has('user_id')): ?>
                <a href="<?= base_url('/logout'); ?>" class="btn btn-danger me-2">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('/login'); ?>" class="btn btn-primary me-2">Login</a>
                <a href="<?= base_url('/register'); ?>" class="btn btn-secondary">Register</a>
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
                            <button class="btn btn-primary save-movie"
                                    data-title="<?= $apiMovie['Title'] ?>"
                                    data-description="Fetched from OMDb API"
                                    data-release_date="<?= $apiMovie['Year'] ?>-01-01"
                                    data-poster="<?= $apiMovie['Poster'] ?>">
                                Save & View Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Local Movies -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 mt-4">
        <?php if (isset($movies) && !empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="col">
                    <div class="card p-2">
                        <?php if (!empty($movie['poster'])): ?>
                            <img src="<?= $movie['poster']; ?>" class="card-img-top movie-poster" alt="Movie Poster">
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h6><?= $movie['title']; ?></h6>
                            <p class="average-rating">⭐ <?= number_format($movie['average_rating'], 1); ?> / 5</p>
                            <a href="<?= base_url('/movie-detail/' . $movie['id']); ?>" class="btn btn-info">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No movies found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- AJAX Handling for Save Movie -->
<script>
    $(document).ready(function(){
        $('.save-movie').click(function(){
            var button = $(this);
            var movieData = {
                title: button.data('title'),
                description: button.data('description'),
                release_date: button.data('release_date'),
                poster: button.data('poster')
            };

            $.ajax({
                url: '<?= base_url('/save-movie-and-redirect'); ?>',
                type: 'POST',
                data: movieData,
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                }
            });
        });
    });
</script>
</body>
</html>
