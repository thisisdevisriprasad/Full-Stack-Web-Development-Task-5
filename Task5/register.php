<?php
session_start();
include 'includes/connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $hashed);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_role'] = 'user';
                header("Location: index.php");
                exit;
            } else {
                $message = "Registration failed, try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register | Food Ordering</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<div class="container col-md-5">
  <h2 class="mb-4">Create Account</h2>

  <?php if ($message): ?>
    <div class="alert alert-warning"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="fullname" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Confirm Password</label>
      <input type="password" name="confirm" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">Register</button>
  </form>

  <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>