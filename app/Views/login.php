<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        <h1 class="text-center">Login</h1>
        <form method="post" action="<?php echo base_url('/authenticate'); ?>">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <!-- Link to Registration Page -->
        <div class="mt-3 text-center">
            <p>New user? <a href="<?php echo base_url('/register'); ?>">Click here to Register</a></p>
        </div>
    </div>
</body>
</html>
