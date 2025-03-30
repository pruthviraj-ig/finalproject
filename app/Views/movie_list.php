<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>

    <!-- I'm using Bootstrap here for styling and layout -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <!-- jQuery for AJAX functionality (used below to save movies from API) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* custom background image with dark overlay */
        body {
            background: url('https://www.themoviedb.org/assets/2/v4/marketing/backgrounds/desktop_login-bg-7a9f09834f0eb20a37d66da50ef6e7d6c2ab3874ed249b67c3f01e97bd9ba3ac.jpg') no-repeat center center fixed;
            background-size: cover;
            background-blend-mode: overlay;
            background-color: #141414;
            color: white;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        /* navbar styles */
        .navbar-custom {
            background: linear-gradient(135deg, #141414, #1f1f1f);
            padding: 15px;
            border-bottom: 2px solid red;
        }

        /* row of movies */
        .movie-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        /* individual movie cards */
        .movie-card {
            background: rgba(31, 31, 31, 0.9);
            border-radius: 10px;
            margin: 10px;
            transition: transform 0.3s;
            width: 200px;
        }

        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px #ff1e56;
        }

        /* poster image */
        .movie-poster {
            width: 100%;
            height: 300px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
        }

        /* movie title with ellipsis if too long */
        .movie-title {
            height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-view-details {
            background-color: #ff1e56;
            color: white;
            border: none;
            width: 100%;
            margin-top: 10px;
        }

        .btn-view-details:hover {
            background-color: #ff4e00;
        }

        .navbar-custom a, .navbar-custom button {
            color: white;
        }

        .navbar-custom button {
            border: 1px solid red;
            background: none;
            margin-left: 10px;
        }

        footer {
            color: #888;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!--  Navbar section with search and login/logout -->
<div class="navbar-custom d-flex justify-content-between align-items-center">
    <h1>🎬 Movie List</h1>

    <div class="d-flex align-items-center">
        <!-- form to search movies (either local or from API) -->
        <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex me-3">
            <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= isset($search) ? $search : ''; ?>">
            <button type="submit" class="btn btn-danger ms-2">Search</button>
        </form>

        <!-- conditionally show login/register or logout based on session -->
        <?php if (session()->has('user_id')): ?>
            <a href="<?= base_url('/logout'); ?>" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <a href="<?= base_url('/login'); ?>" class="btn btn-primary">Login</a>
            <a href="<?= base_url('/register'); ?>" class="btn btn-secondary">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="container-fluid mt-4">

    <!--  Local Movies from DB -->
    <div class="movie-row">
        <?php $counter = 0; ?>
        <?php foreach ($movies as $movie): ?>
            <?php if ($counter >= 16) break; ?> <!-- only showing 16 -->
            <div class="movie-card">
                <img src="<?= $movie['poster'] ?? 'https://via.placeholder.com/200x300'; ?>" class="movie-poster">
                <div class="p-2 text-center">
                    <div class="movie-title"><?= $movie['title']; ?></div>
                    <p>⭐ <?= number_format($movie['average_rating'], 1); ?> / 5</p>
                    <a href="<?= base_url('/movie-detail/' . $movie['id']); ?>" class="btn btn-view-details">View Details</a>
                </div>
            </div>
            <?php $counter++; ?>
        <?php endforeach; ?>
    </div>

    <!-- Movies from OMDb API -->
    <?php if (isset($apiMovies) && !empty($apiMovies)): ?>
        <h2 style="text-align: center; color: #ff1e56;">Movies from OMDb API</h2>
        <div class="movie-row">
            <?php foreach ($apiMovies as $apiMovie): ?>
                <div class="movie-card">
                    <img src="<?= $apiMovie['Poster'] ?? 'https://via.placeholder.com/200x300'; ?>" class="movie-poster">
                    <div class="p-2 text-center">
                        <div class="movie-title"><?= $apiMovie['Title']; ?></div>
                        <p>Year: <?= $apiMovie['Year']; ?></p>
                        <!-- this button triggers an AJAX call to save movie -->
                        <button class="btn btn-view-details save-movie"
                                data-title="<?= $apiMovie['Title']; ?>"
                                data-description="Fetched from OMDb API"
                                data-release_date="<?= $apiMovie['Year']; ?>-01-01"
                                data-poster="<?= $apiMovie['Poster']; ?>">
                            Save & View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!--  Footer section -->
<footer>
    &copy; <?= date('Y'); ?> Pruthviraj Patil (2310346). All Rights Reserved.
</footer>

<!--  AJAX for saving movie from API -->
<script>
    $(document).ready(function(){
        $('.save-movie').click(function(){
            // gather movie data from button's data attributes
            var button = $(this);
            var movieData = {
                title: button.data('title'),
                description: button.data('description'),
                release_date: button.data('release_date'),
                poster: button.data('poster')
            };

            // post it to backend to save it in DB and redirect
            $.ajax({
                url: '<?= base_url('/save-movie-and-redirect'); ?>',
                type: 'POST',
                data: movieData,
                success: function(response) {
                    if (response.redirect) {
                        // go to the movie detail page
                        window.location.href = response.redirect;
                    }
                }
            });
        });
    });
</script>

</body>
</html>
