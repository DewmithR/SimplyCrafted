<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';
include 'includes/admin_header.php';

// Update User
if (isset($_POST['update_user'])) {
    $id = intval($_POST['user_id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();
    header("Location: users.php");
    exit;
}

// Delete User
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);


    $res = $conn->query("SELECT COUNT(*) as total FROM orders WHERE user_id=$user_id");
    $count = $res->fetch_assoc()['total'];

    if ($count > 0) {
        echo "<div class='alert alert-warning text-center'>
                Cannot delete user. They have existing orders.
              </div>";
    } else {
        $conn->query("DELETE FROM users WHERE id=$user_id");
        header("Location: users.php");
        exit;
    }
}


// Get users
$users = $conn->query("SELECT * FROM users WHERE role='customer' ORDER BY id DESC");
?>

<div class="container mt-4">
<h2>Manage Users</h2>

<table class="table table-bordered align-middle text-center">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>

                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $user['id']; ?>">Edit</button>
                <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>


                <div class="modal fade" id="editUser<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <form method="POST">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit User</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                          <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                              <option value="customer" <?php if($user['role']=='customer') echo 'selected'; ?>>Customer</option>
                              <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="update_user" class="btn btn-dark">Update</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<?php include 'includes/admin_footer.php'; ?>
