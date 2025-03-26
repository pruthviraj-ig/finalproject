<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 24px;
            }
            .btn {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Register</h1>
        <form method="post" action="<?php echo base_url('/store'); ?>">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <!-- Link to Login Page -->
        <div class="mt-3 text-center">
            <p>Already a user? <a href="<?php echo base_url('/login'); ?>">Login here</a></p>
        </div>
    </div>
</body>
</html>