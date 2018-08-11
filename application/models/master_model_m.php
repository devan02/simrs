<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	}

	function get_user_info($id_user){
		$sql = "
		SELECT a.*, b.NAMA AS JABATAN, c.NAMA_DEP , d.NAMA_DIV FROM kepeg_pegawai a 
		LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID 
		LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID 
		LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID 
		WHERE a.ID = '$id_user'
		ORDER BY a.ID ASC
		";

		return $this->db->query($sql)->row();
	}

	function is_operator($id_user, $menu){
		$sql = "
		SELECT a.* FROM kepeg_loket_operator a
		JOIN kepeg_loket_akses b ON a.ID_LOKET = b.ID_LOKET
		WHERE a.ID_PEGAWAI = '$id_user' AND b.AKSES = '$menu'
		";

		return $this->db->query($sql)->result();
	}

	function getLoket($id_user, $akses){
		$sql = "
		SELECT b.*, c.KODE FROM kepeg_loket_operator a
		JOIN kepeg_loket b ON a.ID_LOKET = b.ID
		JOIN kepeg_setup_antrian c ON b.KODE_ANTRIAN = c.ID
		JOIN kepeg_loket_akses d ON b.ID = d.ID_LOKET
		WHERE a.ID_PEGAWAI = '$id_user' AND d.AKSES = '$akses'
		";

		return $this->db->query($sql)->row();
	}

	function getJmlAntrian($id_kode_antrian){
		$tgl = date('d-m-Y');

		$sql = "
		SELECT * FROM kepeg_antrian
		WHERE ID_KODE = $id_kode_antrian AND TGL = '$tgl'
		";

		return $this->db->query($sql)->result();
	}

	function get_menu_2($id_pegawai, $id_menu1){
		$sql = "
		SELECT a.* FROM kepeg_menu_2 a 
		JOIN (
			SELECT ID_MENU FROM kepeg_hak_akses
			WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_2'
		) b ON a.ID = b.ID_MENU
		WHERE a.ID_MENU_1 = $id_menu1
        ORDER BY a.URUT ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_menu_3($id_pegawai, $id_menu2){
		$sql = "
		SELECT a.* FROM kepeg_menu_3 a 
		JOIN (
			SELECT ID_MENU FROM kepeg_hak_akses
			WHERE ID_PEGAWAI = $id_pegawai AND KET = 'MENU_3'
		) b ON a.ID = b.ID_MENU
		WHERE a.ID_MENU_2 = $id_menu2
        ORDER BY a.URUT ASC
		";

		return $this->db->query($sql)->result();
	}

	function get_data_asuransi(){
		$sql = "
		SELECT * FROM asr_setup_asuransi
		ORDER BY ID ASC
		";

		return $this->db->query($sql)->result();
	}

	function simpan_log($id_pegawai,$tanggal,$waktu,$keterangan){
		$sql = "
			INSERT INTO kepeg_log_aktifitas(
				ID_PEGAWAI,
				TANGGAL,
				WAKTU,
				KETERANGAN
			) VALUES (
				'$id_pegawai',
				'$tanggal',
				'$waktu',
				'$keterangan'
			)
		";
		$this->db->query($sql);
	}

	function get_dokter(){
		$sql = "
			SELECT 
				a.*,
				b.NAMA_DIV
			FROM kepeg_pegawai a
			LEFT JOIN kepeg_divisi b ON b.ID = a.ID_DIVISI
			WHERE a.STS_LOGIN = '1'
			ORDER BY a.ID DESC
			LIMIT 5
		";
		return $this->db->query($sql)->result();
	}

	function get_tracking_pasien($tanggal){
		$sql = "
			SELECT
				a.*,
				b.NAMA AS NAMA_PASIEN,
				b.JENIS_KELAMIN,
				c.NAMA AS NAMA_POLI
			FROM admum_rawat_jalan a
			LEFT JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			LEFT JOIN admum_poli c ON c.ID = a.ID_POLI
			WHERE a.TANGGAL = '$tanggal'
			ORDER BY a.ID DESC
			LIMIT 5
		";
		return $this->db->query($sql)->result();
	}

	function get_data_dokter(){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
		";
		return $this->db->query($sql)->result();
	}

	function get_dokter_id($id){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
			WHERE a.ID = '$id' 
		";
		return $this->db->query($sql)->row();
	}

	function get_total_all_pasien($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
		";
		return $this->db->query($sql)->result();
	}

	function get_total_pasien_poli($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
			AND a.STS_POSISI = '1'
		";
		return $this->db->query($sql)->result();
	}

	function get_total_pasien_lab($tanggal){
		$sql = "
			SELECT
				a.*
			FROM admum_rawat_jalan a
			WHERE a.TANGGAL = '$tanggal'
			AND a.STS_POSISI = '2'
		";
		return $this->db->query($sql)->result();
	}

	function get_akses_antrian($akses){
		$sql = "
			SELECT
				a.*,
				b.NAMA_LOKET,
				c.KODE,
				c.STS AS STS_LOKET
			FROM kepeg_loket_akses a
			LEFT JOIN kepeg_loket b ON b.ID = a.ID_LOKET
			LEFT JOIN kepeg_setup_antrian c ON c.ID = b.KODE_ANTRIAN
			WHERE a.AKSES = '$akses'
		";
		return $this->db->query($sql)->result();
	}

}

?>