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
  public $table_lunas = 'ews-lunas';
  public $table_bjdpl = 'ews-bjdpl';
  public $table_master_outlet = 'ews-master-outlet';
  
  public function __construct(){
    parent::__construct();
    $this->load->database();
  }
  
  public function get_outlet ($kode) {

    $this->db->select('kode, nama');
    $this->db->where('kode_cabang', $kode);
    $query = $this->db->get($this->table_master_outlet);
    
    return $query->result();
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
  
  public function get_data_kas($idOutlet, $start, $end, $limit) {

    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' => "D",
      'idOutlet' => $idOutlet,
      'realisasi_kas >' => $limit
    );

    $this->db->select('*');
    $this->db->where($multiWhere);
    $this->db->order_by('tanggal', 'asc');

    $query = $this->db->get($this->table_realisasi);
    
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
  
  public function get_last_limit_kas ($idOutlet) {
    
    $this->db->order_by('tanggal', 'desc');
    $this->db->where('idOutlet', $idOutlet);
    $this->db->limit(1);
    
    $query = $this->db->get($this->table_limit);

    return $query->row();
  }

  public function get_data_bank($idOutlet, $start, $end, $limit) {
    
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' => "D",
      'idOutlet' => $idOutlet,
      'realisasi_bank >' => $limit
    );

    $this->db->select('*');
    $this->db->where($multiWhere);
    $this->db->order_by('tanggal', 'asc');
    $query = $this->db->get($this->table_realisasi_bank);
    
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

  public function get_last_limit_bank ($idOutlet) {
    
    $this->db->order_by('tanggal', 'desc');
    $this->db->where('idOutlet', $idOutlet);
    $this->db->limit(1);
    
    $query = $this->db->get($this->table_limit_bank);

    return $query->row();
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
  
  public function get_data_lunas($idOutlet, $start, $end) {
    
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D"
    );
    
    $this->db->select('tanggal,SUM(rekening) as rekening, SUM(nasabah) as nasabah, SUM(up) as up');
    foreach ($idOutlet as $key => $value) {
      $this->db->or_where('idOutlet', $value->kode);
    }
    $this->db->where($multiWhere);
    $this->db->group_by('tanggal');
    $query = $this->db->get($this->table_lunas);
    
    return $query->result();
    
  }
  
  public function get_detail_lunas($idOutlet, $start, $end) {
    
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D"
    );
    
    $this->db->select('idOutlet, SUM(rekening) as rekening, SUM(nasabah) as nasabah, SUM(up) as up');
    foreach ($idOutlet as $key => $value) {
      $this->db->or_where('idOutlet', $value->kode);
    }
    $this->db->where($multiWhere);
    $this->db->group_by('idOutlet');
    $query = $this->db->get($this->table_lunas);
    
    return $query->result();
    
  }
  
  public function get_data_bjdpl($idOutlet, $start, $end) {
    
    $multiWhere = array(
      'tanggal <=' => $start,
      'tanggal >=' => $end,
      'status !=' =>"D",
      'idOutlet'=>$idOutlet
    );
    
    $this->db->select('*')->where($multiWhere)->order_by('tanggal', 'desc');
    $query = $this->db->get($this->table_bjdpl);
    
    return $query->result();
    
  }
  
  public function get_detail_bjdpl($id) {
    
    $this->db->select('*')->where('id', $id)->order_by('tanggal', 'desc');
    $query = $this->db->get($this->table_bjdpl);
    
    return $query->result();
    
  }

}
?>