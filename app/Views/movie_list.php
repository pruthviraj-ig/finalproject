<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
</head>
<body>
    <h1>Movie List</h1>
    <a href="<?php echo base_url('/add-movie'); ?>">Add Movie</a>
    <ul>
        <?php if (isset($movies) && !empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <li><?php echo $movie['title']; ?> (Released: <?php echo $movie['release_date']; ?>)</li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No movies found. Add a new movie!</li>
        <?php endif; ?>
    </ul>
</body>
</html>
