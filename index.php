<?php 
session_start();
if( !isset($_SESSION["login"]) ) {
	header ("Location: login.php");
	exit;
} 
require 'functions.php';

// pagination
// konfigurasi
$jumlahDataPerHalaman = 3;
$jumlahData = count(query("SELECT * FROM databuku"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = ( isset ($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

$databuku = query("SELECT * FROM databuku LIMIT $awalData, $jumlahDataPerHalaman");

if( isset($_POST["cari"]) ) {
	$databuku = cari($_POST["keyword"]);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Halaman Admin</title>
</head>
<body>

<a href="logout.php">Log Out</a>

<h1>Management Data Buku</h1>

<button type="button" class="btn btn-primary">
<a href="tambah.php">Tambah data data buku</a>
</button>

<br><br>

<form action="" method="post">
	<input type="text" name="keyword" size="30" autofocus placeholder="Search" autocomplete="off">
	<button type="submit" name="cari">cari!</button>
</form>
<br><br>

<!-- navigasi -->
<?php if( $halamanAktif > 1 ) : ?>
<a href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
<?php endif; ?>

<?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
	<?php if( $i == $halamanAktif ) : ?>
     <a href="?halaman=<?=$i; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
     <?php else : ?>
     <a href="?halaman=<?=$i; ?>"><?= $i; ?></a>
     <?php endif;  ?>
<?php endfor; ?>

<?php if( $halamanAktif < $jumlahHalaman ) : ?>
<a href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
<?php endif; ?>

<br>

<table border="1" cellpadding="10" cellspacing="0">
	<tr>
		<th>No.</th>
		<th>Aksi</th>
		<th>Gambar</th>
		<th>Category</th>
		<th>Nama Buku</th>
		<th>Deskripsi</th>
	</tr>
	<?php $i = 1; ?>
	<?php foreach( $databuku as $row ) : ?>
	<tr>
		<td><?= $i; ?></td>
		<td>
			<a href="ubah.php?id=<?= $row["id"]; ?>">ubah</a> |
			<a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
		</td>
		<td><img src="img/<?= $row["gambar"]; ?>" width="50"></td>
		<td><?= $row["category"] ?></td>
		<td><?= $row["nama_buku"] ?></td>
		<td><?= $row["deskripsi"] ?></td>
	</tr>
	<?php $i++; ?>
<?php endforeach; ?>
</table>

</body>
</html>