<?php
// Bagian PHP untuk membuat user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    define('WP_USE_THEMES', false);
    require_once('wp-load.php');
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    if (username_exists($username)) {
        $error = 'Username sudah terdaftar.';
    } elseif (email_exists($email)) {
        $error = 'Email sudah terdaftar.';
    } else {
        $user_id = wp_create_user($username, $password, $email);
        
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('administrator');
            $success = true;
            // Hapus file setelah berhasil (opsional)
            // unlink(__FILE__);
        } else {
            $error = $user_id->get_error_message();
        }
    }
}
?>

<!-- Form HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Buat Admin WordPress</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #2271b1; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Buat Admin WordPress</h1>
    
    <?php if (isset($error)): ?>
        <p class="error">Error: <?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <p class="success">Admin berhasil dibuat!</p>
        <p>Username: <?php echo htmlspecialchars($username); ?></p>
        <p>Password: <?php echo htmlspecialchars($password); ?></p>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="text" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit">Buat Admin</button>
        </form>
    <?php endif; ?>
</body>
</html>