<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class Model_ews extends CI_Model {
  
  public $table_nasabah = 'ews-nasabah';
  public $table_karyawan = 'ews-karyawan';
  public $table_limit = 'ews-batas';
  public $table_realisasi = 'ews-realisasi';
  public $table_limit_bank = 'ews-batas-bank';
  public $table_realisasi_bank = 'ews-realisasi-bank';
  public $table_transaksi = 'ews-transaksi';

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function get_data_nasabah($idOutlet, $start, $end) {

    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'idOutlet'=>$idOutlet
    );

    $this->db->select('nik, customerName, SUM(up) as upTotal')->where($multiWhere);
    $this->db->group_by('nik, customerName');
		$query = $this->db->get($this->table_nasabah);
		
    return $query->result();
  } 

  public function get_detail_nasabah($nik, $start, $end) {
   
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'nik'=>$nik
    );

    $this->db->select('*')->where($multiWhere)->order_by('tanggal',' desc');
		$query = $this->db->get($this->table_nasabah);
		
    return $query->result(); 
  }

  public function get_data_karyawan($idOutlet, $start, $end) {
    
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'idOutlet'=>$idOutlet
    );

    $this->db->select('*');
    $this->db->where($multiWhere);
		$query = $this->db->get($this->table_karyawan);
		
    return $query->result();
  }

  public function get_detail_karyawan($nip, $start, $end) {

    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'nip'=>$nip
    );

    $this->db->select('*')->where($multiWhere)->order_by('tanggal',' desc');
		$query = $this->db->get($this->table_karyawan);
		
    return $query->result();
  }

  public function get_data_kas($idOutlet, $start, $end) {

    $multiWhere = array(
      't1.tanggal <=' => $start,
      't1.tanggal >=' => $end,
      't1.status !=' =>"D",
      't1.idOutlet'=>$idOutlet
    );

    $this->db->select('t1.id, t1.idOutlet, t1.tanggal, t1.realisasi_kas, t2.tanggal, t2.limit_kas, t1.createdBy, t1.createdDate')->from($this->table_realisasi. ' t1');
    $this->db->join($this->table_limit. ' t2', 't1.idOutlet = t2.idOutlet AND t1.tanggal = t2.tanggal');
    $this->db->where($multiWhere);
    $this->db->where('t1.realisasi_kas > t2.limit_kas');
    $query = $this->db->get();

    return $query->result();

  }

  public function get_detail_kas($id, $idOutlet, $start, $end) {

    $multiWhere = array(
      't1.tanggal <=' => $start,
      't1.tanggal >=' => $end,
      't1.status !=' =>"D",
      't1.idOutlet'=>$idOutlet,
      't1.id'=>$id
    );

    $this->db->select('t1.id, t1.idOutlet, t1.tanggal, t1.realisasi_kas, t2.tanggal, t2.limit_kas, t1.createdBy, t1.createdDate')->from($this->table_realisasi. ' t1');
    $this->db->join($this->table_limit. ' t2', 't1.idOutlet = t2.idOutlet AND t1.tanggal = t2.tanggal');
    $this->db->where($multiWhere);
    $this->db->where('t1.realisasi_kas > t2.limit_kas');
    $query = $this->db->get();

    return $query->result();

  }

  public function get_data_bank($idOutlet, $start, $end) {

    $multiWhere = array(
      't1.tanggal <=' => $start,
      't1.tanggal >=' => $end,
      't1.status !=' =>"D",
      't1.idOutlet'=>$idOutlet
    );

    $this->db->select('t1.id, t1.idOutlet, t1.tanggal, t1.realisasi_bank, t2.tanggal, t2.limit_bank, t1.createdBy, t1.createdDate')->from($this->table_realisasi_bank. ' t1');
    $this->db->join($this->table_limit_bank. ' t2', 't1.idOutlet = t2.idOutlet AND t1.tanggal = t2.tanggal');
    $this->db->where($multiWhere);
    $this->db->where('t1.realisasi_bank > t2.limit_bank');
    $query = $this->db->get();

    return $query->result();

  }

  public function get_detail_bank($id, $idOutlet, $start, $end) {

    $multiWhere = array(
      't1.tanggal <=' => $start,
      't1.tanggal >=' => $end,
      't1.status !=' =>"D",
      't1.idOutlet'=>$idOutlet,
      't1.id'=>$id
    );

    $this->db->select('t1.id, t1.idOutlet, t1.tanggal, t1.realisasi_bank, t2.tanggal, t2.limit_bank, t1.createdBy, t1.createdDate')->from($this->table_realisasi_bank. ' t1');
    $this->db->join($this->table_limit_bank. ' t2', 't1.idOutlet = t2.idOutlet AND t1.tanggal = t2.tanggal');
    $this->db->where($multiWhere);
    $this->db->where('t1.realisasi_bank > t2.limit_bank');
    $query = $this->db->get();

    return $query->result();

  }

  public function get_data_transaksi($idOutlet, $start, $end) {

    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'idOutlet'=>$idOutlet
    );

    $this->db->select('*')->where($multiWhere)->order_by('tanggal', 'desc');
    $query = $this->db->get($this->table_transaksi);

    return $query->result();

  }

  public function get_detail_transaksi($id) {
    
    $this->db->select('*')->where('id', $id)->order_by('tanggal', 'desc');
    $query = $this->db->get($this->table_transaksi);

    return $query->result();

  }

}
?>