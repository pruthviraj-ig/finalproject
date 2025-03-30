<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- here i am using bootstrap css to style form -->
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

        /* this is the box where user will register */
        .register-box {
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px #ff1e56; /* pinkish glow just for effect */
            width: 400px;
        }

        .register-box h1 {
            text-align: center;
            color: #ff1e56;
            margin-bottom: 30px;
        }

        /* this is for dark styled input fields */
        .form-control {
            background-color: #141414;
            border: 1px solid #ff1e56;
            color: white;
        }

        /* when user clicks input this makes pink border */
        .form-control:focus {
            outline: none;
            box-shadow: none;
            border-color: #ff4e00;
        }

        /* button that registers the user */
        .btn-custom {
            background-color: #ff1e56;
            color: white;
            border: none;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #ff4e00;
        }

        /* just some link styling */
        a {
            color: #ff1e56;
            text-decoration: none;
        }

        a:hover {
            color: #ff4e00;
        }

        /* this is footer for name */
        footer {
            margin-top: 20px;
            text-align: center;
            color: grey;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <!-- i made this section to let users register -->
    <div class="register-box">
        <h1>Register</h1>

        <!-- this form will send data to store route -->
        <form method="post" action="<?= base_url('/store'); ?>">

            <!-- input for username -->
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <!-- input for email -->
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <!-- input for password -->
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <!-- register button -->
            <button type="submit" class="btn btn-custom">Register</button>
        </form>

        <!-- if already user, i give login link here -->
        <div class="mt-3 text-center">
            <p>Already a user? <a href="<?= base_url('/login'); ?>">Login Here</a></p>
        </div>
    </div>

    <!-- just my name at bottom -->
    <footer>
        &copy; Pruthviraj Patil - 2310346
    </footer>

</body>
</html>
