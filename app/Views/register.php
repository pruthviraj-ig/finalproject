<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #141414;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        .register-box {
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px #ff1e56;
            width: 400px;
        }

        .register-box h1 {
            text-align: center;
            color: #ff1e56;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: #141414;
            border: 1px solid #ff1e56;
            color: white;
        }

        .form-control:focus {
            outline: none;
            box-shadow: none;
            border-color: #ff4e00;
        }

        .btn-custom {
            background-color: #ff1e56;
            color: white;
            border: none;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #ff4e00;
        }

        a {
            color: #ff1e56;
            text-decoration: none;
        }

        a:hover {
            color: #ff4e00;
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
    <div class="register-box">
        <h1>Register</h1>
        <form method="post" action="<?= base_url('/store'); ?>">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-custom">Register</button>
        </form>
        <div class="mt-3 text-center">
            <p>Already a user? <a href="<?= base_url('/login'); ?>">Login Here</a></p>
        </div>
    </div>

    <!-- Copyright Notice -->
    <footer>
        &copy; Pruthviraj Patil - 2310346
    </footer>
</body>
</html>
