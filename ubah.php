<?php
session_start();
if( !isset($_SESSION["login"]) ) {
	header ("Location: login.php");
	exit;
}

require 'functions.php';

// ambil data diURL
$id = $_GET["id"];

// query data databuku berdasarkan id
$databuku = query("SELECT * FROM databuku WHERE id = $id")[0];

// cek apakah tombol submit sudah ditekan apa belum
if( isset($_POST["submit"]) ) {
	

	// cek apakah data berhasil diubah
	if( ubah($_POST) > 0 ) {
		echo "
              <script>
              alert('data berhasil diubah!');
              document.location.href = 'index.php'
              </script>  
            ";
	} else {
		echo "
              <script>
              alert('data gagal diubah!');
              document.location.href = 'index.php'
              </script>  
		";
	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ubah data buku</title>
</head>
<body>

<h1>Ubah data buku</h1>

<form action="" method="post" enctype="multipart/form-data">
	
	<input type="hidden" name="id" value="<?= $databuku["id"]; ?>">
	<input type="hidden" name="gambarLama" value="<?= $databuku["gambar"]; ?>">
	<ul>
		<li>
			<label for="nama_buku">Nama buku: </label>
			<input type="text" name="nama_buku" id="nama_buku" value="<?= $databuku["nama_buku"] ?>">
		</li>
		<li>
			<label for="category">Category : </label>
			<input type="text" name="category" id="category" required value="<?= $databuku["category"] ?>">
		</li>
		<li>
			<label for="deskripsi">Deskripsi : </label>
			<input type="text" name="deskripsi" id="deskripsi" value="<?= $databuku["deskripsi"] ?>">
		</li>
		<li>
			<label for="gambar">Gambar : </label>
			<img src="img/<?= $databuku['gambar']; ?>" width="40"> <br>
			<input type="file" name="gambar" id="gambar">
		</li>
		<li>
			<button type="submit" name="submit">Ubah Data!</button>
		</li>
	</ul>
</form>

</body>
</html>