<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cek data</title>
</head>
<body>
    <?php
        include 'koneksi.php';
        $query = 'SELECT "JOB_TITLE" FROM JOBS';
        $result = $conn->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo $row["JOB_TITLE"];
            echo "<br>";
        }
    ?>
</body>
</html>