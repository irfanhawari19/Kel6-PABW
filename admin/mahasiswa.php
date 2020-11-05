<?php
    require('header.php');
    require('../action/mahasiswa.php');
    $listMahasiswa = new ListMahasiswa();
    if(isset($_POST["hapus"])){
    	$id = $_POST["id"];
    	$listMahasiswa->deleteMahasiswa($id);
    }
?>
	<h2>Mahasiswa</h2>
	<a class="btn btn-new-primary" href="./mahasiswa_tambah.php">Tambah</a>
	<table class="w-100">
		<thead class="text-center">
			<tr>
				<td>NIM</td>
				<td>Nama</td>
				<td>Prodi</td>
				<td>Action</td>
			</tr>
		</thead>
		<tfoot class="text-center">
			<tr>
				<td>NIM</td>
				<td>Nama</td>
				<td>Prodi</td>
				<td>Action</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				foreach ($listMahasiswa->getAllMahasiswa() as $mahasiswa) {
					echo 
					'<tr>
						<td>'.$mahasiswa->getNIM().'</td>
						<td>'.$mahasiswa->getNama().'</td>
						<td>'.$mahasiswa->getNamaProdi().'</td>
						<td>
							<form method="post">

							<a href="./mahasiswa_edit.php?NIM='.$mahasiswa->getNIM().'" class="btn btn-warning">
								Edit
							</a>
								<input type="hidden" value="'.$mahasiswa->getNIM().'" name="id">
								<button type="submit" class="btn btn-danger" name="hapus">
									Delete
							</form>

						</td> 
					</tr>';
				}
			?>
		</tbody>
	</table>
<?php
    require('footer.php');
?>
