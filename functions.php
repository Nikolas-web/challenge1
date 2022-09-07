<?php 
$conn = mysqli_connect("localhost", "root", "", "challenges");

function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}

function tambah($data) {
	global $conn;

	$nama_buku = htmlspecialchars($data["nama_buku"]);
	$category = htmlspecialchars($data["category"]);
	$deskripsi = htmlspecialchars($data["deskripsi"]);

	// upload gambar
	$gambar = upload();
	if( !$gambar ) {
		return false;
	}

	$query = "INSERT INTO databuku
              VALUES
	          ('', '$nama_buku', '$category', '$deskripsi', '$gambar')
	          ";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);

}

function upload() {
	$namaFile = $_FILES['gambar']['name'];
	$ukuranFile = $_FILES['gambar']['size'];
	$error = $_FILES['gambar']['error'];
	$tmpName = $_FILES['gambar']['tmp_name'];

	// cek apakah ada gambar yang diupload
	if( $error === 4 ) {
		echo "<script>
		          alert('pilih gambar terlebih dahulu!');
		     </script>";

		     return false;
	}

	// cara mengecek apakah yang diupload adalah gambar
	$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
	
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
		echo "<script>
		          alert('yang anda upload bukan gambar!');
		     </script>";

		     return false;
	}
    
    // cara cek apakah gambar ukuran gambar terlalu besar
    if( $ukuranFile > 1000000 ) {
    	echo "<script>
		          alert('ukuran gambar terlalu besar!');
		     </script>";

		     return false;
    }
     
    // lolos pengecekan gambar siap diupload
    // generate nama_buku gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;
    
    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

function hapus($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM databuku WHERE id = $id");
	return mysqli_affected_rows($conn);
}

function ubah($data) {
	global $conn;
    
    $id = $data["id"];
	$nama_buku = htmlspecialchars($data["nama_buku"]);
	$category = htmlspecialchars($data["category"]);
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$gambarlama = htmlspecialchars($data["gambarLama"]);

	// cek apakah user baru pilih gambar baru atau tidak
	if( $_FILES['gambar']['error'] === 4 ) {
		$gambar = $gambarLama;
	} else {
		$gambar = upload();
	}

	$query = "UPDATE databuku SET
              nama_buku = '$nama_buku',
	          category = '$category',
	          deskripsi = '$deskripsi',
	          gambar = '$gambar'
	         WHERE id = $id
	          ";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function cari($keyword) {
	$query = "SELECT * FROM databuku 
	           WHERE
	           nama_buku LIKE '%$keyword%' OR
	           category LIKE '%$keyword%' OR
               deskripsi LIKE '%$keyword%'
	           ";
	return query($query);
}

function registrasi($data) {
	global $conn;

	$username = strtolower(stripslashes($data["username"]));
	$password = mysqli_real_escape_string($conn, $data["password"]);
	$password2 = mysqli_real_escape_string($conn, $data["password2"]);

	//cek username sudah ada atau belum
	$result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username' ");
	if( mysqli_fetch_assoc($result) ) {
		echo "<script>
		       alert('username sudah terdaftar')
		      </script>";
		return false;
	}
    
    // cek konfirmasi password
    if( $password !== $password2 ) {
    	echo "<script>
                      alert('konfirmasi password tidak sesuai');
    	      </script>";
    	return false;
    }
    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);


   // tambahkan user baru ke data base
    mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password') ");
    return mysqli_affected_rows($conn);

}

?>