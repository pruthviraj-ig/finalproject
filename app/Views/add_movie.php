<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
</head>
<body>
    <h1>Add Movie</h1>
    <form method="post" action="<?php echo base_url('/save-movie'); ?>">
        <label>Title:</label>
        <input type="text" name="title"><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Release Date:</label>
        <input type="date" name="release_date"><br>

        <label>Poster URL:</label>
        <input type="text" name="poster"><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>
