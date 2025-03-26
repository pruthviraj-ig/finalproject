<!DOCTYPE html>
<html>
<head>
    <title><?= $movie['title']; ?> - Movie Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #141414;
            color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px #ff1e56;
        }

        .img-fluid {
            border-radius: 10px;
            box-shadow: 0 0 5px #ff1e56;
        }

        .btn-custom {
            background-color: #ff1e56;
            color: white;
            border: none;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: #ff4e00;
        }

        .form-control, textarea {
            background-color: #141414;
            color: white;
            border: 1px solid #ff1e56;
        }

        .form-control:focus, textarea:focus {
            border-color: #ff4e00;
            outline: none;
            box-shadow: none;
        }

        .review-box {
            background-color: #1f1f1f;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        h2, h3 {
            color: #ff1e56;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            color: grey;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <!-- Movie Poster -->
        <div class="col-md-4 text-center">
            <img src="<?= $movie['poster']; ?>" class="img-fluid mb-3" alt="Movie Poster">
        </div>

        <!-- Movie Details -->
        <div class="col-md-8">
            <h2><?= $movie['title']; ?></h2>
            <p><strong>From OMDb API</strong></p>
            <p><strong>Release Date:</strong> <?= $movie['release_date']; ?></p>
            <p><strong>Average Rating:</strong> ⭐ <?= number_format($averageRating, 1); ?> / 5</p>

            <!-- Review Form -->
            <?php if (session()->has('user_id')): ?>
                <form class="review-form mt-3" method="post" action="<?= base_url('/save-review'); ?>">
                    <input type="hidden" name="movie_id" value="<?= $movie['id']; ?>">
                    <div class="mb-3">
                        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="review" class="form-control" placeholder="Your review..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Submit Review</button>
                </form>
            <?php else: ?>
                <p><strong>Please <a href="<?= base_url('/login'); ?>" style="color: #ff1e56;">Login</a> to leave a review.</strong></p>
            <?php endif; ?>

            <!-- Display Reviews -->
            <h3 class="mt-4">Reviews</h3>
            <div id="reviews-section">
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to add one!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-box">
                            <strong style="color: #ff1e56;"><?= $review['username']; ?>:</strong> <?= $review['rating']; ?>/5 ⭐
                            <p><?= $review['review']; ?></p>

                            <!-- Edit & Delete Options -->
                            <?php if (session()->get('user_id') == $review['user_id']): ?>
                                <!-- Edit Review -->
                                <form method="post" action="<?= base_url('/edit-review/' . $review['id']); ?>" class="d-inline edit-review-form">
                                    <textarea name="review" class="form-control mb-1"><?= $review['review']; ?></textarea>
                                    <input type="number" name="rating" min="1" max="5" value="<?= $review['rating']; ?>" class="form-control mb-1">
                                    <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                </form>

                                <!-- Delete Review -->
                                <button class="btn btn-danger btn-sm delete-review" data-id="<?= $review['id']; ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Copyright Notice -->
<footer>
    &copy; Pruthviraj Patil - 2310346
</footer>

<!-- AJAX Handling for Reviews -->
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
            if (confirm('Are you sure you want to delete this review?')) {
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
