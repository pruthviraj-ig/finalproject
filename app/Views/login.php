<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <!-- I'm using Bootstrap here  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <style>
        /* custom dark theme vibes here */
        body {
            background-color: #141414;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* full screen height */
            margin: 0;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        /* this is the login card box */
        .login-box {
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px #ff1e56; /* glowy red box effect */
            width: 350px;
        }

        .login-box h1 {
            text-align: center;
            color: #ff1e56;
            margin-bottom: 30px;
        }

        /* styling input fields to match theme */
        .form-control {
            background-color: #141414;
            border: 1px solid #ff1e56;
            color: white;
        }

        /* removing default highlight */
        .form-control:focus {
            outline: none;
            box-shadow: none;
            border-color: #ff4e00; /* orange glow on focus */
        }

        /* custom login button */
        .btn-custom {
            background-color: #ff1e56;
            color: white;
            border: none;
            width: 100%; /* make it full width */
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

    <!-- login card box -->
    <div class="login-box">
        <h1>Login</h1>

        <!-- form for username and password -->
        <!-- after submit, it goes to /login/authenticate -->
        <form method="post" action="<?= base_url('/login/authenticate'); ?>">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-custom">Login</button>
        </form>

        <!-- just linking to register page if user doesn’t have an account yet -->
        <div class="mt-3 text-center">
            <p>New user? <a href="<?= base_url('/register'); ?>">Register Here</a></p>
        </div>
    </div>

    <!-- basic footer -->
    <footer>
        &copy; Pruthviraj Patil - 2310346
    </footer>

</body>
</html>
