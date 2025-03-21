<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Movie List</h1>
            <div>
                <?php if (session()->has('user_id')): ?>
                    <a href="<?php echo base_url('/add-movie'); ?>" class="btn btn-primary">Add Movie</a>
                    <a href="<?php echo base_url('/logout'); ?>" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="<?php echo base_url('/login'); ?>" class="btn btn-primary">Login to Add Movies</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="get" action="<?php echo base_url('/movies'); ?>" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for movies..." value="<?php echo isset($search) ? $search : ''; ?>">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
        </form>

        <!-- Display Local Movies -->
        <div class="row">
            <?php if (!empty($movies)): ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $movie['title']; ?></h5>
                                <p class="card-text">Released: <?php echo $movie['release_date']; ?></p>
                                <p class="card-text"><?php echo $movie['description']; ?></p>
                                <?php if (!empty($movie['poster'])): ?>
                                    <img src="<?php echo $movie['poster']; ?>" alt="Poster" class="img-fluid mb-3">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Display API Movies -->
        <?php if (isset($apiMovies) && $apiMovies): ?>
            <div class="mt-5">
                <h2>Search Results from OMDb API</h2>
                <div class="row">
                    <?php foreach ($apiMovies['Search'] as $apiMovie): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $apiMovie['Title']; ?></h5>
                                    <p class="card-text">Year: <?php echo $apiMovie['Year']; ?></p>
                                    <img src="<?php echo $apiMovie['Poster']; ?>" alt="Poster" class="img-fluid mb-3">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
