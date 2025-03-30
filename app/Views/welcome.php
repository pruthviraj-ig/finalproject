<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to Movie Review System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      text-align: center;
    }

    h1 {
      font-size: 3rem;
      color: #ff1e56;
      text-shadow: 0 0 10px #ff1e56, 0 0 20px #ff1e56;
      animation: fadeIn 1.5s ease-in-out;
    }

    h4 {
      color: #ff4e00;
      margin-bottom: 30px;
      animation: fadeIn 2s ease-in-out;
    }

    .btn-custom {
      background-color: #ff1e56;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      margin: 10px;
      transition: transform 0.3s, background-color 0.3s, box-shadow 0.3s;
      box-shadow: 0 0 10px #ff1e56, 0 0 20px #ff1e56;
    }

    .btn-custom:hover {
      background-color: #ff4e00;
      transform: scale(1.05);
      box-shadow: 0 0 15px #ff4e00, 0 0 30px #ff4e00;
    }

    .btn-outline-light {
      border-radius: 12px;
      padding: 10px 20px;
    }

    footer {
      position: absolute;
      bottom: 10px;
      color: #888;
      font-size: 12px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 576px) {
      h1 {
        font-size: 2rem;
      }
      .btn-custom {
        width: 80%;
      }
    }
  </style>
</head>
<body>

  <h1>🎬 Welcome to Movie Review System</h1>

  <?php if (session()->has('username')): ?>
    <h4>Hello, <?= session()->get('username'); ?> 👋</h4>
    <a href="<?= base_url('/movies'); ?>" class="btn btn-custom">Let’s Explore</a>
    <a href="<?= base_url('/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
  <?php else: ?>
    <a href="<?= base_url('/login'); ?>" class="btn btn-custom">Login</a>
    <a href="<?= base_url('/register'); ?>" class="btn btn-outline-light">Register</a>
  <?php endif; ?>

  <footer>
    &copy; <?= date('Y'); ?> Pruthviraj Patil (2310346)
  </footer>

</body>
</html>
