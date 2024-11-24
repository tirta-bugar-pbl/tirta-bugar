<?php
    include 'koneksi.php';
    session_start();

    if(isset($_GET['token'])){
        // variabel check token
        $token = $_GET['token'];

        // mengambil data dari database
        $queryCheck = "SELECT token_verify, status_verify FROM admin WHERE token_verify = '$token' LIMIT 1";
        $resultCheck = $conn->query($queryCheck);
        $rowCheck = $resultCheck->fetch(PDO::FETCH_ASSOC);
        
        if($rowCheck['status_verify'] == 0){
            $tokenVerify = $rowCheck['token_verify']; 
            $queryUpdate = "UPDATE admin SET status_verify = '1' WHERE token_verify = '$tokenVerify'";
            $conn->query($queryUpdate);

            if($queryUpdate){
                echo "<script>
                    alert('Akun berhasil di verifikasi');
                    setTimeout(function() {
                        window.location.href = 'admin-login.php';
                        }, 1000);
                </script>";
            } else {
                echo "<script>
                    alert('Akun belum di verifikasi');
                    setTimeout(function() {
                        window.location.href = 'admin-login.php';
                        }, 1000);
                </script>";
            }
        } else {
            echo "<script>
                alert('Akun belum di verifikasi');
                setTimeout(function() {
                    window.location.href = 'admin-login.php';
                    }, 1000);
            </script>";
        } 
    }

?>