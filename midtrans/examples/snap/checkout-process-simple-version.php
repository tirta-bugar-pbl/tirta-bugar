<?php
    // This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
    // Please refer to this docs for snap popup:
    // https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview
    namespace Midtrans;
    require_once dirname(__FILE__) . '/../../Midtrans.php';

    // Set Your server key
    // can find in Merchant Portal -> Settings -> Access keys
    Config::$serverKey = 'SB-Mid-server-GSV-k2VgZTn9cvfmTdckGfpz';
    Config::$clientKey = 'SB-Mid-client-SN5wP3iwzZPi-8ZL';

    // non-relevant function only used for demo/example purpose
    printExampleWarningMessage();

    // Uncomment for production environment
    // Config::$isProduction = true;
    Config::$isSanitized = Config::$is3ds = true;
    include '../../../koneksi.php';
    session_start();
    $order_id = $_GET['order_id'];

    // Mengambil data dari session
    if(isset($_SESSION['form_data'])) {
        $nama = $_SESSION['form_data']['nama'];
        $email = $_SESSION['form_data']['email'];
        $nomor_telepon = $_SESSION['form_data']['nomor_telepon'];
        $durasi = $_SESSION['form_data']['durasi'];
    } else {
        echo "<script>alert('Data pendaftaran tidak ditemukan.'); window.location.href='../../../daftar.php';</script>";
    }

    if($durasi == '1') {
        $harga_paket = 100000;
        $keterangan = '8x Pertemuan';
        $keteranganDurasi = '1 Bulan';
    } else if ($durasi == '2') {
        $harga_paket = 185000;
        $keterangan = 'Bebas Datang';
        $keteranganDurasi = '1 Bulan';
    } else {
        $harga_paket = 500000;
        $keterangan = 'Bebas Datang';
        $keteranganDurasi = '3 Bulan';
    }

    $biaya_pendaftaran = 50000;
    $total = $harga_paket + $biaya_pendaftaran;

    function rupiah($angka){
        return "Rp " . number_format($angka,0,',','.');
    }

    // Required
    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $total, // no decimal allowed for creditcard
    );

    // Optional
    $item_details = array (
        array(
            'id' => $durasi,
            'price' => $harga_paket,
            'quantity' => 1,
            'name' => "Paket Fitness Regullar " . $keteranganDurasi
        ),
        array(
            'id' => 1,
            'price' => 50000,
            'quantity' => 1,
            'name' => "Pendaftaran"
        ),
    );

    // Optional
    $customer_details = array(
        'first_name'    => $nama,
        'last_name'     => "",
        'email'         => $email,
        'phone'         => $nomor_telepon,
    );
    // Fill transaction details
    $transaction = array(
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
    );

    $snap_token = '';
    try {
        $snap_token = Snap::getSnapToken($transaction);
    }
    catch (\Exception $e) {
        echo $e->getMessage();
    }

    function printExampleWarningMessage() {
        if (strpos(Config::$serverKey, 'your ') != false ) {
            echo "<code>";
            echo "<h4>Please set your server key from sandbox</h4>";
            echo "In file: " . __FILE__;
            echo "<br>";
            echo "<br>";
            echo htmlspecialchars('Config::$serverKey = \'SB-Mid-server-GSV-k2VgZTn9cvfmTdckGfpz\';');
            die();
        } 
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Tirta Bugar Fitness</title>
    <link rel="stylesheet" href="../../../css/style.css">
    <link rel="stylesheet" href="../../../css/pembayaran.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php if(isset($_SESSION['form_data'])): ?>
    <h2 class="title-payment">Payment</h2>
    <div class="payment-box">
        <h3 class="title-detail-payment">Detail Pesanan</h3>
        <h5 class="title-invoice">#<?php echo $order_id; ?></h5>
        <div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Nama</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($nama) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Nomor Telepon</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($nomor_telepon) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Jenis Paket</p>
                    <p>:</p>
                </div>
                <p>Regular</p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Durasi</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($keteranganDurasi) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Keterangan</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($keterangan) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Harga Paket</p>
                    <p>:</p>
                </div>
                <p><?= rupiah($harga_paket) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Biaya Pendaftaran</p>
                    <p>:</p>
                </div>
                <p><?= rupiah($biaya_pendaftaran) ?></p>
            </div>
        </div>
        <div class="detail-payment-group total container">
            <h4 class="label-total-amount">Total Biaya</h4>
            <h5 class="total-amount"><?= rupiah($total) ?></h5>
        </div>
    </div>
    <button type="submit" name="submit" class="btn-payment" id="pay-button">Bayar</button>
    <?php else: ?>
    <div class="alert alert-danger">
        <p>Tidak ada data pendaftaran yang ditemukan. Silakan kembali ke halaman <a href="daftar.php">pendaftaran</a>.</p>
    </div>
    <?php endif; ?>
    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('<?php echo $snap_token?>', {
                onSuccess: function (result) {
                    window.location.href = "../../../pembayaran-berhasil.php?order_id=<?php echo $order_id; ?>";
                },
            });
        };
    </script>
</body>
</html>
