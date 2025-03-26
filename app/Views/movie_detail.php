<!DOCTYPE html>
<html>
<head>
    <title><?= $movie['title']; ?> - Movie Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?= $movie['poster']; ?>" class="img-fluid mb-3" alt="Movie Poster">
        </div>
        <div class="col-md-8">
            <h2><?= $movie['title']; ?></h2>
            <p><?= $movie['description']; ?></p>
            <p><strong>Release Date:</strong> <?= $movie['release_date']; ?></p>
            <p><strong>Average Rating:</strong> ⭐ <?= number_format($averageRating, 1); ?> / 5</p>

            <?php if (session()->has('user_id')): ?>
                <form class="review-form" method="post" action="<?= base_url('/save-review'); ?>">
                    <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                    <div class="mb-3">
                        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="review" class="form-control" placeholder="Your review..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            <?php endif; ?>

            <h3 class="mt-4">Reviews</h3>
            <div id="reviews-section">
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to add one!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $review['username']; ?>:</strong> <?= $review['rating']; ?>/5 ⭐
                            <p><?= $review['review']; ?></p>

                            <!-- Edit & Delete Buttons for User's Own Review -->
                            <?php if (session()->get('user_id') == $review['user_id']): ?>
                                <!-- Edit Review Form -->
                                <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="d-inline edit-review-form">
                                    <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                    <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                    <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                </form>

                                <!-- Delete Review Button -->
                                <button class="btn btn-danger btn-sm delete-review" data-id="<?= $review['id']; ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Handling for Review Submission, Editing, and Deletion -->
<script>
    $(document).ready(function(){
        $('.review-form').submit(function(e){
            e.preventDefault();

            var form = $(this);
            var movieId = form.find('input[name="movie_id"]').val();
            var formData = form.serialize();

            $.ajax({
                url: '<?= base_url('/save-review'); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Review submitted successfully!');
                        location.reload();  // Refresh the page to show the new review
                    }
                }
            });
        });

        // Handle Delete Review Button Click
        $('.delete-review').click(function(){
            var reviewId = $(this).data('id');
            var confirmed = confirm('Are you sure you want to delete this review?');

            if (confirmed) {
                $.ajax({
                    url: '<?= base_url('/delete-review/'); ?>' + reviewId,
                    type: 'GET',
                    success: function(response) {
                        alert('Review deleted successfully!');
                        location.reload();
                    }
                });
            }
        });

        // Handle Edit Review Form Submission
        $('.edit-review-form').submit(function(e){
            e.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert('Review updated successfully!');
                    location.reload();
                }
            });
        });
    });
</script>
</body>
</html>
