<?php
    session_start();
    include 'koneksi.php';
    require 'send-email.php';

    if (isset($_POST['submit'])) {
        // variabel email
        $email = $_POST['email'];

        //generate token
        $token = bin2hex(random_bytes(16));
        $token_hash = hash('sha256', $token);

        //generate token expire
        $expire = date('Y-m-d H:i:s', time() + 60 * 30);

        // query update token
        $queryResetCode = "UPDATE admin SET reset_token_hash = '$token_hash', reset_token_expires_at = '$expire' WHERE email = '$email'";
        $resultResetCode = $conn->query($queryResetCode);

        if($resultResetCode) {
            // variabel untuk mengirim email
            $subject = 'Reset Password';
            $body = "silahkan buka link <a href='http://localhost/tirta-bugar/admin-new-pw.php?token=$token'>di sini</a> untuk mengganti password kamu. Terima kasih";

            sendEmail($email, "anonymus", $subject, $body);

            echo "<script>alert('Pesan sudah dikirim, silahkan cek email anda');</script>";
        } else {
            echo "<script>alert('Email kamu tidak terdaftar, silahkan registrasi');</script>";
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
    <link rel="stylesheet" href="css/admin-forgot-pw.css?v=<?php echo time(); ?>">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="forgot-layout container">
        <h2 class="forgot-title">Forgot Password</h2>
        <form method="POST">
            <div class="form-forgot container">
                <div class="form-group container">
                    <label for="email">Masukkan Email anda :</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit" class="btn-forgot">Send Email</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>