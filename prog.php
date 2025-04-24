<?php
session_start();
include('db.php'); // Make sure $conn = mysqli_connect(...) is defined here

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $password = $_POST['password'];

    // Secure with prepared statements
    $stmt = $conn->prepare("SELECT * FROM register_table WHERE V_id = ? AND password = ?");
    $stmt->bind_param("ss", $uid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['Name'];
        $_SESSION['voter_id'] = $row['V_id']; // Store V_id for later use in voting
        header("Location: index.php"); // Redirect after successful login
        exit();
    } else {
        $error = "Invalid UID or Password. Or you may need to register.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('login page img.jpeg') no-repeat center center fixed;
      background-size: cover;
      overflow: hidden;
    }
    .top-right {
      position: absolute;
      top: 20px;
      right: 30px;
      color: white;
      font-size: 18px;
      font-weight: bold;
    }
    .login-wrap {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }
    .login-btn {
      background: #111;
      padding: 20px 40px;
      border-radius: 15px;
      color: deeppink;
      font-size: 28px;
      cursor: pointer;
      transition: all 0.4s ease-in-out;
      box-shadow: 0 0 40px rgba(255, 20, 147, 0.3);
    }
    .login-form {
      display: none;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      margin-top: 20px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      animation: slideDown 0.5s ease-out;
    }
    .login-form input {
      width: 250px;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
    }
    .login-form button {
      padding: 10px 20px;
      background-color: darkorange;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    header {
      background-color: #2c3e50;
      color: #fff;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1em 2em;
    }
    .navbar .logo {
      font-size: 1.8em;
      font-weight: bold;
    }
    .navbar .nav-links {
      list-style-type: none;
      display: flex;
    }
    .navbar .nav-links li {
      margin-left: 20px;
    }
    .navbar .nav-links a {
      color: #fff;
      text-decoration: none;
      transition: color 0.3s;
    }
    .navbar .nav-links a:hover,
    .navbar .nav-links a.active {
      color: #00bcd4;
    }
    a {
      color: white;
    }
  </style>
</head>
<body>

<header>
  <nav class="navbar">
    <div class="logo"><a href="index.php">Digital Voting System</a></div>
    <ul class="nav-links">
      <li><a href="index.html">Home</a></li>
      <li><a href="register.html">Register</a></li>
      <li><a href="login.php" class="active">Login</a></li>
      <li><a href="about.html">About</a></li>
      <li><a href="stats.html">Current Stats</a></li>
      <li><a href="candidates.php">Candidates</a></li>
    </ul>
  </nav>
</header>

<!-- Show Welcome message if logged in -->
<?php if (isset($_SESSION['username'])): ?>
  <div class="top-right">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
<?php endif; ?>

<div class="login-wrap">
  <!-- Initial Login Button -->
  <div class="login-btn" onclick="showForm()">LOGIN</div>

  <!-- Login Form -->
  <form class="login-form" method="POST" action="">
    <input type="text" name="uid" placeholder="Enter UID" required><br>
    <input type="password" name="password" placeholder="Enter Password" required><br>
    <button type="submit">Login</button>
    <div style="margin-top: 10px">
      <a href="register.html" style="color: white; text-decoration: underline;">Signup</a>
    </div>
  </form>

  <!-- Show Error Message -->
  <?php if (isset($error)): ?>
    <p style="color: red; font-weight: bold;"> <?php echo $error; ?> </p>
  <?php endif; ?>
</div>

<script>
  function showForm() {
    document.querySelector('.login-btn').style.display = 'none';
    document.querySelector('.login-form').style.display = 'block';
  }
</script>
</body>
</html>