<?php
    include 'koneksi.php';
    require 'send-email.php';

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $verify_token = md5(rand());

        // mengecek akun yang sudah terdaftar
        $sql = "SELECT email FROM admin WHERE email = '$email'";
        $result = $conn->query($sql);
        $rowDuplicateEmail = $result->fetch(PDO::FETCH_ASSOC);

        if (empty($username) || empty($email) || empty($password)) {
            echo "<script>
                alert('Wajib isi Form !');
            </script>";
        } else if($rowDuplicateEmail){
            echo "<script>
                alert('Email sudah terdaftar, silahkan gunakan email yang lain!');
            </script>";
        } else {
            $sql = "INSERT INTO admin (username, email, password,token_verify,status_verify) VALUES ('$username', '$email', '$hash_pass', '$verify_token', 0)";

            if ($conn->query($sql)) {
                 // variabel untuk mengirim email
                $subject = 'Registrasi Akun';
                $body = "Akun anda sudah di registrasi, silahkan buka link <a href='http://localhost/tirta-bugar/verify-regist.php?token=$verify_token'>di sini</a> untuk melakukan verifikasi";
                sendEmail($email, $username, $subject, $body);

                echo "<script>
                    alert('Akun anda sudah berhasil diregistrasi, silahkan cek email untuk melakukan verifikasi');
                    setTimeout(function() {
                        window.location.href = 'admin-login.php';
                        }, 1000);
                </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="css/admin-register.css?v=<?php echo time(); ?>">
     <!-- link favicon -->
     <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
        <!-- link google font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="register-title">Register</h2>
        <form method="POST">
            <div class="container">
                <div class="form-group container">
                    <label for="username">Username</label>
                    <input type="username" name="username" id="username">
                </div>
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
                <div class="button-group">
                    <button type="submit" name="submit" class="btn-register">Register</button>
                    <p class="text-login">Sudah Punya Akun? <a href="admin-login.php">Login</a></p>
                </div>
            </div>
        </form>
    </div>
    <script>
        // fungsi show password
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