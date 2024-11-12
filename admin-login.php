<?php
    session_start();
    include 'koneksi.php';

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo "<script>alert('Wajib isi Form !');</script>";
        } else {
            $sql = "SELECT * FROM admin WHERE email = '$email'";
            $result = $conn->query($sql);
            $user = $result->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['id_admin'] = $user['id_admin'];
                header('Location: admin.php');
                exit();
            } else {
                echo "<script>alert('Email atau password salah!');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-login.css?v=<?php echo time(); ?>">
        <!-- link google font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="login-title">Login</h2>
        <form method="POST">
            <div class="container">
                <div class="form-group container">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="form-group container">
                    <label for="password">Password</label>
                    <div class="pw-group container">
                        <input type="password" name="password" id="password">
                        <img src="assets/show-pw.svg" alt="show-pw" onclick="showPassword()">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit" class="btn-login">Login</button>
                    <p>Don’t have account? <a href="admin-register.php" target="_blank">Register</a></p>
                </div>
            </div>
        </form>
    </div>
    <script>
        function showPassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>