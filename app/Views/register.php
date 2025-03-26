<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #141414;
            color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-custom {
            background: rgba(31, 31, 31, 0.9);
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 15px #ff1e56;
            border: 1px solid #ff1e56;
        }

        h1 {
            text-align: center;
            color: #ff1e56;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: #1f1f1f;
            color: white;
            border: 1px solid #ff1e56;
        }

        .form-control:focus {
            background-color: #1f1f1f;
            color: white;
            border-color: #ff1e56;
            box-shadow: none;
        }

        .btn-custom {
            background-color: #ff1e56;
            border: none;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #ff4e00;
        }

        .link-section p {
            text-align: center;
            margin-top: 15px;
            color: white;
        }

        .link-section a {
            color: #ff1e56;
            text-decoration: none;
            font-weight: bold;
        }

        .link-section a:hover {
            text-decoration: underline;
            color: #ff4e00;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <h1>Register</h1>
        <form method="post" action="<?= base_url('/store'); ?>">
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
            <button type="submit" class="btn btn-custom">Register</button>
        </form>
        <div class="link-section">
            <p>Already a user? <a href="<?= base_url('/login'); ?>">Login Here</a></p>
        </div>
    </div>
</body>
</html>
