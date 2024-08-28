<?php
// Koneksi ke database
$connect = mysqli_connect('localhost', 'root', '', 'db_projectsig');

// Cek koneksi
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set variabel dari POST request
$lat_long = $_POST['latlong'];
$nama_tempat = $_POST['nama_tempat'];
$kecamatan = $_POST['kecamatan'];
$kabupaten = $_POST['kabupaten'];
$kode_pos = $_POST['kode_pos'];
$no_telepon = $_POST['no_telepon'];
$keterangan = $_POST['keterangan'];

// Query untuk input data ke tabel lokasi
$insert = mysqli_query($connect, "INSERT INTO rumahsakit SET
    lat_long='$lat_long',
    nama_tempat='$nama_tempat',
    kecamatan='$kecamatan',
    kabupaten='$kabupaten',
    kode_pos='$kode_pos',
    no_telepon='$no_telepon',
    keterangan='$keterangan'
");

// Cek apakah data berhasil diinput
if ($insert) {
    // Redirect kembali ke index.php
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($connect);
}

// Tutup koneksi
mysqli_close($connect);
?>
