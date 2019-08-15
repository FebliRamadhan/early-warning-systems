<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	var $table = "";
	// var $column_order = array(null, 'namaData','keteranganData','orderData','statusData');
	// var $column_search = array('namaData','keteranganData','orderData','statusData');
	// var $order = array('id' => 'asc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	private function _get_datatables_query($table="",$params="",$column="",$order="")
	{

		if ($params) {
			$this->db->where($params);
		}

		$this->db->from($table);

		$i = 0;

		foreach ($column as $item)
		{
			if(@$_POST['search']['value'])
			{

				if($i===0)
				{
					$this->db->group_start();
					$this->db->like($item, @$_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, @$_POST['search']['value']);
				}

				if(count($column) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

        if(isset($_POST['order'])) // here order processing
        {
        	$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
        	$order = $this->order;
        	$this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($table="",$params="",$column="")
    {
    	$this->_get_datatables_query($table,$params,$column);
    	if(@$_POST['length'] != -1)
    		$this->db->limit(@$_POST['length'], @$_POST['start']);
    	$query = $this->db->get();
    	return $query->result();
    }

    function count_filtered($table="",$params="",$column="")
    {
    	$this->_get_datatables_query($table,$params,$column);
    	$query = $this->db->get();
    	return $query->num_rows();
    }

    public function count_all($table="",$params="",$column="")
    {
    	$this->_get_datatables_query($table,$params,$column);
    	return $this->db->count_all_results();
    }

    public function get($table="default",$column="",$params="",$sort="",$order="",$limit="")
    {
    	$table = $table=="default" ? $this->table : $table;
    	$this->db->select($column);
    	$this->db->order_by($sort,$order);
    	$this->db->limit($limit);
    	if ($params) {
    		$this->db->where($params);
    	}
    	if ($column) {
    		$this->db->distinct();
    	}
    	$query = $this->db->get($table);
    	return $query->result_array();
    }

    public function get_where($table="default",$params="",$sort="",$order="",$limit="",$group_by="",$select="",$offset="")
    {
    	$table = $table=="default" ? $this->table : $table;
    	$this->db->order_by($sort,$order);
        $this->db->limit($limit, $offset);
        $this->db->group_by($group_by);
        $this->db->where($params);
        if ($select) {
          $this->db->select($select);
			// $this->db->distinct();
      }
      $query = $this->db->get($table);
      return $query->result_array();
  }


  public function join($table="default",$column="",$table2="",$params="",$sort="",$order="",$limit="",$group_by="", $offset="",$like="")
  {
     $table = $table=="default" ? $this->table : $table;
     $this->db->select($column);
     $this->db->from($table);
     foreach ($table2 as $row){
      $this->db->join($row['table'], $row['parameter'],'left');
  }

  if(!empty($params))
  {
      $this->db->where($params);	
  }
  $this->db->distinct();
  $this->db->order_by($sort,$order);
  $this->db->limit($limit, $offset);
  $this->db->group_by($group_by);

  if ($like) {
      foreach ($like as $resultLike) {
       $this->db->like($resultLike['column'], $resultLike['keyword'],$resultLike['method']);
   }
}
$query = $this->db->get();
return $query->result_array();
}

public function insert_data($table="default",$data="")
{
 $table = $table=="default" ? $this->table : $table;
 $this->db->trans_start();
 $this->db->insert($table, $data);
 $insert_id = $this->db->insert_id();
 $this->db->trans_complete();
 return  $insert_id;
}

public function update_data($table="default",$data="",$params="")
{
 $table = $table=="default" ? $this->table : $table;
 $this->db->update($table, $data, $params);
}

public function delete_data($table="default",$params="")
{
 $table = $table=="default" ? $this->table : $table;
 $this->db->delete($table, $params);
}

function getOrder($table="",$field="",$params="")
{

 empty($params) ? $where = "" : $where = $this->db->where($params);

 $table = $table=="default" ? $this->table : $table;
 $this->db->select("MAX($field) as kd_max");		
 $where;
 $query = $this->db->get($table);

 $kd = "";
 if($query->num_rows()>0){
  foreach($query->result() as $k){
   $tmp = ((int)$k->kd_max)+1;
   $kd = sprintf($tmp);
}
}else{
  $kd = "1";
}
return $kd;
}


}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */