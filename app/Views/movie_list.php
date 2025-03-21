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

        <?php if (isset($movies) && !empty($movies)): ?>
            <div class="row">
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
                                <?php if (session()->has('user_id')): ?>
                                    <a href="#" class="btn btn-success">Add Review</a>
                                <?php else: ?>
                                    <a href="<?php echo base_url('/login'); ?>" class="btn btn-warning">Login to Add Review</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No movies found. Add a new movie!</div>
        <?php endif; ?>
    </div>
</body>
</html>
