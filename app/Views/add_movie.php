<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Add Movie</h1>
        <form method="post" action="<?php echo base_url('/save-movie'); ?>">
            <div class="mb-3">
                <label>Movie Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>Alternative Description (Optional):</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            
            <div class="mb-3">
                <label>Alternative Release Date (Optional):</label>
                <input type="date" name="release_date" class="form-control">
            </div>
            
            <div class="mb-3">
                <label>Alternative Poster URL (Optional):</label>
                <input type="text" name="poster" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Save Movie</button>
        </form>
    </div>
</body>
</html>
