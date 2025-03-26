<!DOCTYPE html>
<html>
<head>
    <title><?= $movie['title']; ?> - Movie Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('https://www.themoviedb.org/assets/2/v4/marketing/backgrounds/desktop_login-bg-7a9f09834f0eb20a37d66da50ef6e7d6c2ab3874ed249b67c3f01e97bd9ba3ac.jpg') no-repeat center center fixed;
            background-size: cover;
            background-blend-mode: overlay;
            background-color: #141414;
            color: white;
            font-family: Arial, sans-serif;
        }

        .container-custom {
            background: rgba(31, 31, 31, 0.9);
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .btn-submit, .btn-warning, .btn-danger {
            background-color: #ff1e56;
            color: white;
            border: none;
        }

        .btn-submit:hover, .btn-warning:hover, .btn-danger:hover {
            background-color: #ff4e00;
        }

        .review-section {
            margin-top: 30px;
        }

        .review-box {
            background-color: #1f1f1f;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .review-box strong {
            color: #ff1e56;
        }

        .edit-review-form textarea, .edit-review-form input {
            background-color: #1f1f1f;
            color: white;
            border: 1px solid #ff1e56;
        }

        .edit-review-form button {
            margin-top: 5px;
        }

    </style>
</head>
<body>
<div class="container container-custom">
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?= $movie['poster']; ?>" class="img-fluid mb-3" alt="Movie Poster" style="border-radius: 10px;">
        </div>
        <div class="col-md-8">
            <h2><?= $movie['title']; ?></h2>
            <p><?= $movie['description']; ?></p>
            <p><strong>Release Date:</strong> <?= $movie['release_date'] ?? 'Unknown'; ?></p>
            <p><strong>Average Rating:</strong> ⭐ <?= number_format($averageRating, 1); ?> / 5</p>

            <?php if (session()->has('user_id')): ?>
                <form class="review-form mt-4" method="post" action="<?= base_url('/save-review'); ?>">
                    <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                    <div class="mb-3">
                        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="review" class="form-control" placeholder="Your review..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-submit btn-block">Submit Review</button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning mt-4">You must <a href="<?= base_url('/login'); ?>" class="text-danger">Login</a> to add a review.</div>
            <?php endif; ?>

            <div class="review-section mt-5">
                <h3>Reviews</h3>
                <div id="reviews-section">
                    <?php if (empty($reviews)): ?>
                        <p>No reviews yet. Be the first to add one!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-box">
                                <strong><?= $review['username']; ?>:</strong> <?= $review['rating']; ?>/5 ⭐
                                <p><?= $review['review']; ?></p>

                                <?php if (session()->get('user_id') == $review['user_id']): ?>
                                    <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="d-inline edit-review-form mt-2">
                                        <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                        <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                        <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                    </form>
                                    <button class="btn btn-danger btn-sm delete-review" data-id="<?= $review['id']; ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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
            var formData = form.serialize();

            $.ajax({
                url: '<?= base_url('/save-review'); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Review submitted successfully!');
                        location.reload();
                    }
                }
            });
        });

        $('.delete-review').click(function(){
            var reviewId = $(this).data('id');
            var confirmed = confirm('Are you sure you want to delete this review?');

            if (confirmed) {
                $.ajax({
                    url: '<?= base_url('/delete-review/'); ?>' + reviewId,
                    type: 'GET',
                    success: function() {
                        alert('Review deleted successfully!');
                        location.reload();
                    }
                });
            }
        });

        $('.edit-review-form').submit(function(e){
            e.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                success: function() {
                    alert('Review updated successfully!');
                    location.reload();
                }
            });
        });
    });
</script>
</body>
</html>
