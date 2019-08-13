<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class Table_nasabah extends CI_Model {
  
  public $table = 'ews-nasabah';

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function get_data($start, $end) {
    $multiWhere = array('tanggal <=' => $start, 'tanggal >=' => $end, 'status !=' =>"D");
    $this->db->select('ktp, customerName, SUM(up) as upTotal')->where($multiWhere);
    $this->db->group_by('ktp, customerName');
		$query = $this->db->get($this->table);
		
    return $query->result();
  }
   
}
?>