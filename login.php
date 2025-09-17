<?php
session_start();
include 'config/db.php';

// redirect to index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        // Check password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect admin
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow p-4" style="max-width:400px; width:100%;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
            <p class="mb-1">Don’t have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
