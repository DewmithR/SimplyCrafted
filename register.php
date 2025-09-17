<?php
session_start();
include 'config/db.php';

// redirect
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);


    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username OR email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $res = $check->get_result();

    if ($res->num_rows > 0) {
        $error = "Username or email already taken!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        if ($stmt->execute()) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow p-4" style="max-width:450px; width:100%;">
        <h3 class="text-center mb-4">Create an Account</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
            </div>
            <button type="submit" name="register" class="btn btn-dark w-100">Register</button>
        </form>
        <div class="mt-3 text-center">
            <p class="mb-1">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
