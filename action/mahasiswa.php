<?php
	require_once('../action/frs.php');
	require_once('../action/civitas.php');
	class Mahasiswa extends Civitas{
		private $NIM;
		private $Prodi;
		private $koneksi;
		private $idDosen;
		function mahasiswa($NIM, $namaMahasiswa, $idProdi, $idDosen){
			$this->NIM = $NIM;
			$this->koneksi = create_connection();
			$this->idDosen = $idDosen;
			parent::__construct($namaMahasiswa, $idProdi);
		}
		function setNIM($NIM){
			$this->NIM = $NIM;
		}
		function getNIM(){
			return $this->NIM;
		}
		function getIdDosen(){
			return $this->idDosen;
		}
		function dropSemester($semester){
			$hapus = $this->koneksi->prepare("DELETE FROM frs where NIM=:NIM and semester=:semester");
			$hapus->bindParam(":NIM",$this->NIM);
			$hapus->bindParam(":semester", $semester);
			$hapus->execute();
		}
		function getAllFrs(){
			$query = $this->koneksi->prepare("SELECT * FROM frs where NIM=:NIM");
			$query->bindParam(':NIM', $this->NIM);
			$query->execute();
			$hasil = [];
			while($tampil=$query->fetch()){
				$hasil[] = new FRS($tampil['idFrs']);
			}
			return $hasil;
		}
		function getFrsBySemester($idFrs){
			$query = $this->koneksi->prepare("SELECT * FROM ambil_matkul inner join where idFrs=:idFrs");
			$query->bindParam(':NIM', $this->NIM);
			$query->bindParam(':idFrs', $idFrs);
			$query->execute();
			$hasil = [];
			while($tampil=$query->fetch()){
				$hasil = new FRS($tampil['id_frs'], $tampil['NIM'], $tampil['semester'], $tampil['persetujuan_dosen']);
			}
			return $hasil;
		}
		function tambahSemester(){
			$koneksi = $this->koneksi;
			$query = $koneksi->prepare("SELECT count(semester) as jumlah_semester from frs where NIM=:NIM group by NIM");
			$query->bindParam(':NIM', $this->NIM);
			$query->execute();
			if($jumlah_semester = $query->fetch()){
				$jumlah_semester = $jumlah_semester['jumlah_semester']+1;
			}else{
				$jumlah_semester = 1;
			}
			$tambah = $koneksi->prepare("INSERT INTO frs value(0, :NIM, :semester, 0)");
			$tambah->bindParam(':NIM', $this->NIM);
			$tambah->bindParam(':semester', $jumlah_semester);
			$tambah->execute();
		}
		function setujuiFrs($semester){
			$query = $this->koneksi->prepare("UPDATE frs set persetujuanDosen=1 where NIM=:NIM and semester=:semester");
			$query->bindParam(':NIM', $this->NIM);
			$query->bindParam(':semester', $semester);
			$query->execute();
		}
	}
	class ListMahasiswa{
		public function __construct()
	    {
	    	$this->koneksi = create_connection();
	    }
		function getAllMahasiswa(){
			$ambil=$this->koneksi->prepare("select * from mahasiswa");
			$ambil->execute();
			while($tampil=$ambil->fetch()){
	            $hasil[] = new Mahasiswa($tampil['NIM'], $tampil['namaMahasiswa'], $tampil['idProdi'], $tampil['idDosen']);
			}
			return $hasil;
		}
		function getMahasiswaById($NIM){
			$ambil = $this->koneksi->prepare("SELECT * from mahasiswa where NIM=:NIM");
			$ambil->bindParam(':NIM', $NIM);
			$ambil->execute();
			$row = $ambil->fetch();
			return new Mahasiswa($row['NIM'], $row['namaMahasiswa'], $row['idProdi'], $row['idDosen']);
		}
		function deleteMahasiswa($id){
			$delete = $this->koneksi->prepare('DELETE FROM mahasiswa where NIM=:NIM');
			$delete->bindParam(':NIM', $id);
			$delete->execute();
		}

		function saveMahasiswa($NIMLama, $Mahasiswa){
			try{
				$koneksi = create_connection();
				$simpan = $koneksi->prepare("UPDATE mahasiswa set NIM=:NIMbaru, idProdi=:idProdi, namaMahasiswa=:namaMahasiswa, idDosen=:idDosen where NIM=:NIMlama");
				$simpan->bindParam(':namaMahasiswa',$Mahasiswa->getNama());
				$simpan->bindParam(':NIMbaru', $Mahasiswa->getNim());
				$simpan->bindParam(':idProdi', $Mahasiswa->getIdProdi());
				$simpan->bindParam(':idDosen', $Mahasiswa->getIdDosen());
				$simpan->bindParam(':NIMlama', $NIMLama);
				$simpan->execute();
				header('Location:../admin/mahasiswa.php');

			}catch(PDOException $Exception){
				 throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
			}
		}
		function insertMahasiswa($Mahasiswa){
			$koneksi = $this->koneksi;
			$masuk = $koneksi->prepare('insert into mahasiswa value (:NIM, :namaMahasiswa, :idProdi, :idDosen)');
			$masuk->bindParam(":NIM", $Mahasiswa->getNIM());
			$masuk->bindParam(":namaMahasiswa", $Mahasiswa->getNama());
			$masuk->bindParam(":idProdi", $Mahasiswa->getIdProdi());
			$masuk->bindParam(":idDosen", $Mahasiswa->getIdDosen());
			$masuk->execute();
			header('Location:../admin/mahasiswa.php');
		}

	}
?>
