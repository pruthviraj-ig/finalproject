<!DOCTYPE html>
<html>
<head>
    <title>Movie List</title>
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
            overflow-x: hidden;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #141414, #1f1f1f);
            padding: 15px;
            border-bottom: 2px solid red;
        }

        .movie-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

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

        .movie-poster {
            width: 100%;
            height: 300px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
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
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar-custom d-flex justify-content-between align-items-center">
    <h1>🎬 Movie List</h1>
    
    <div class="d-flex align-items-center">
        <form method="get" action="<?= base_url('/movies'); ?>" class="d-flex me-3">
            <input type="text" name="search" class="form-control" placeholder="Search movies..." value="<?= isset($search) ? $search : ''; ?>">
            <button type="submit" class="btn btn-danger ms-2">Search</button>
        </form>
        
        <?php if (session()->has('user_id')): ?>
            <a href="<?= base_url('/logout'); ?>" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <a href="<?= base_url('/login'); ?>" class="btn btn-primary">Login</a>
            <a href="<?= base_url('/register'); ?>" class="btn btn-secondary">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="container-fluid mt-4">
    <!-- Displaying Movies -->
    <div class="movie-row">
        <?php $counter = 0; ?>
        <?php foreach ($movies as $movie): ?>
            <?php if ($counter >= 16) break; ?> <!-- Stop rendering movies after 16 -->
            <div class="movie-card">
                <img src="<?= $movie['poster'] ?? 'https://via.placeholder.com/200x300'; ?>" class="movie-poster">
                <div class="p-2 text-center">
                    <h6><?= $movie['title']; ?></h6>
                    <p>⭐ <?= number_format($movie['average_rating'], 1); ?> / 5</p>
                    <a href="<?= base_url('/movie-detail/' . $movie['id']); ?>" class="btn btn-view-details">View Details</a>
                </div>
            </div>
            <?php $counter++; ?>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.movie-row').on('wheel', function(e) {
            e.preventDefault();
            $(this).scrollLeft($(this).scrollLeft() + e.originalEvent.deltaY);
        });
    });
</script>

</body>
</html>
