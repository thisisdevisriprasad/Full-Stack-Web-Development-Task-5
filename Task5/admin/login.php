<?php
session_start();
include('../includes/connect.php');

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: index.php");
            exit;
        }
    }
    $error = "Invalid username or password.";
}
?>
<!doctype html>
<html>
<head><title>Admin Login</title></head>
<body>
<h2>Admin Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
<form method="post">
  <label>Username</label><br>
  <input name="username" required><br>
  <label>Password</label><br>
  <input type="password" name="password" required><br><br>
  <button name="login">Login</button>
</form>
</body>
</html>