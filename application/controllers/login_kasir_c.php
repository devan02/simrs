<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_kasir_c extends CI_Controller {

	function __construct(){
	parent::__construct();
	  $sess_user = $this->session->userdata('masuk_rs');
    $id_user = $sess_user['id'];
	  if($id_user != "" || $id_user != null){
	     redirect('portal');
	  }
	}

	function index(){
		$data = array(
			'title' => 'Login',
			'subtitle' => 'Login',
			'msg' => '',
		);
		$this->load->view('login_kasir_v',$data);
	}

	function login(){
		$user = $this->input->post('username');
		$pass = md5(md5($this->input->post('password')));
		$shift = $this->input->post('shift');

		$msg = '';

		$sql = "SELECT
							a.*,
							b.NAMA AS JABATAN,
							c.NAMA_DEP,
							d.NAMA_DIV
						FROM kepeg_pegawai a
						LEFT JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
						LEFT JOIN kepeg_departemen c ON a.ID_DEPARTEMEN = c.ID
						LEFT JOIN kepeg_divisi d ON a.ID_DIVISI = d.ID
						WHERE a.USERNAME = '$user' AND a.PASSWORD = '$pass'
					";
		$qry = $this->db->query($sql);
		$jumlah = $qry->num_rows();

		if($jumlah != 0){
			$data = $qry->row();
			$sess_array = array(
				'id'		 => $data->ID,
				'username'	 => $data->USERNAME,
				'id_klien'	 => 13,
				'id_departemen'	=> $data->ID_DEPARTEMEN,
				'id_divisi'	 => $data->ID_DIVISI,
				'departemen' => $data->NAMA_DEP,
				'divisi' => $data->NAMA_DIV,
				'level' => $data->LEVEL,
				'sts_login' => $data->STS_LOGIN,
				'shift' => $shift
			);

			$this->session->set_userdata('masuk_rs', $sess_array);
			$session_data = $this->session->userdata('masuk_rs');

			$id_login = $data->ID;
			$tanggal = date('d-m-Y');
			$tz_object = new DateTimeZone('Asia/Jakarta');
			$datetime = new DateTime();
			$format = $datetime->setTimezone($tz_object);
			$waktu = $format->format('H:i:s');

			$sql_log = "UPDATE kepeg_pegawai SET STS_LOGIN = '1',DATE_LOG = '$tanggal',TIME_LOG = '$waktu' WHERE ID = '$id_login'";
			$this->db->query($sql_log);

			if($data->LEVEL == 'Kasir AA'){
				redirect('apotek/ap_portal_kasir_aa_c');
			}else if($data->LEVEL == 'Kasir Rajal'){
				redirect('apotek/ap_kasir_rajal_c');
			}

		}else{
			$this->session->set_flashdata('gagal','1');
			redirect('login_kasir_c');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
