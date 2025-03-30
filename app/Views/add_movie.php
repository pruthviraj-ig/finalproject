<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>

    <!-- I'm using Bootstrap here for quick styling  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <style>
        /* I added some basic responsiveness for mobile users */
        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 24px; /* smaller title for small screens */
            }
            .btn {
                width: 100%; /* full-width button on small devices */
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">Add Movie</h1>

        <!-- I used a form to submit the movie data to the backend -->
        <!-- this one sends the data to the save-movie route -->
        <form method="post" action="<?php echo base_url('/save-movie'); ?>">

            <!-- This is where user adds the movie title -->
            <div class="mb-3">
                <label>Movie Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <!-- Optional description for the movie -->
            <!-- I used a textarea because description can be long -->
            <div class="mb-3">
                <label>Alternative Description (Optional):</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <!-- Here the user can pick a release date -->
            <!-- It's optional but useful info -->
            <div class="mb-3">
                <label>Alternative Release Date (Optional):</label>
                <input type="date" name="release_date" class="form-control">
            </div>

            <!-- Poster URL field, so I can show an image on the movie card -->
            <!-- If it's empty, I can show a placeholder -->
            <div class="mb-3">
                <label>Alternative Poster URL (Optional):</label>
                <input type="text" name="poster" class="form-control">
            </div>

            <!-- Button to actually save the movie -->
            <!-- I kept it Bootstrap styled -->
            <button type="submit" class="btn btn-primary">Save Movie</button>
        </form>
    </div>

</body>
</html>
