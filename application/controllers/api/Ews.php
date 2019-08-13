<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Ews extends CI_Controller{

  function __construct() {

    parent::__construct();
    $this->load->model('table_nasabah');
    $this->load->helper('json');
  }

  function nasabah() {

    $codeResponse = 200;
    $responseData = array();
    $responseCode = "00";
    $responseDesc = "";
    $responseError = false;

    $dateStart = date("Y-m-d");
    $dateEnd = date("Y-m-d", strtotime("-2 week"));

    $result  = $this->table_nasabah->get_data($dateStart, $dateEnd);
    
    foreach ($result as $key => $rows) {
      if ($rows->upTotal > 100000000) {
        array_push($responseData, $rows);
      }  
    }
    
    $response = resultJson($responseError, $responseCode, $responseDesc, @$responseData);

    echo json_encode($response);
    
  }
 }
?>