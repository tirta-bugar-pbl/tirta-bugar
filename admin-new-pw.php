<?php
    session_start();
    include 'koneksi.php';

    $token = $_GET['token'];

    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM admin WHERE reset_token_hash = '$token_hash'";

    $result = $conn->query($sql);
    $user = $result->fetch(PDO::FETCH_ASSOC);

    if ($user === null) {
        die("token not found");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die("token has expired");
    }

    if (isset($_POST['submit'])) {
        // variabel password
        $password = $_POST['password'];
        $cfrPassword = $_POST['confirmartion-password'];

        if (strlen($password) < 8) {
            echo "<script>alert('Password harus lebih dari 8 characters');</script>";
        }

        if ($password !== $cfrPassword) {
            echo "<script>alert('Password tidak cocok dengan konfirmasi password');</script>";
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $queryEditPassword = "UPDATE admin SET password = '$password_hash', reset_token_hash = NULL,reset_token_expires_at = NULL WHERE id_admin = $user[id_admin]";

        $resultUpdatedPassword = $conn->query($queryEditPassword);

        if($resultUpdatedPassword) {
            echo "<script>alert('Password berhasil diubah'); setTimeout(function() {window.location.href = 'admin-login.php';}, 1000);</script>";
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
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin-new-pw.css?v=<?php echo time(); ?>">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="new-pw-title">Reset Your Password</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?= $token ?>">
            <div class="form-new-pw container">
                <div class="form-group container">
                    <label for="password">Masukan password baru</label>
                    <div class="pw-group container">
                        <input type="password" name="password" id="password" required>
                        <img src="assets/show-pw.svg" alt="show-pw" onclick="showPassword('password')">
                    </div>
                </div>
                <div class="form-group container">
                    <label for="password">Konfirmasi password baru</label>
                    <div class="pw-group container">
                        <input type="password" name="confirmartion-password" id="confirmartion-password" required>
                        <img src="assets/show-pw.svg" alt="show-pw" onclick="showPassword('confirmartion-password')">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit" class="btn-new-pw">Confirm</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        // fungsi show password
        function showPassword(type) {
            let passwordInput = document.getElementById(type);
            if (passwordInput.type === "password") {
                return passwordInput.type = "text";
            } else {
                return passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>