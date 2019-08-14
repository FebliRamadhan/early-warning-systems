<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Ews extends API_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Model_ews');
    }
 
    public function nasabah_post()
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('idOutlet','idOutlet','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $responseData = array();
 
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));
            $result  = $this->Model_ews->get_data_nasabah($dateStart, $dateEnd);
 
            foreach ($result as $key => $rows) {
                if ($rows->upTotal > 100000000) {
                    array_push($responseData, $rows);
                }  
            }
 
            $responseError = false;
            $responseCode = "00";
            $responseDesc = "approve";
 
 
        }else{
            $message = $this->form_validation->error_array();
            $responseError = true;
            $responseCode = "01";
            $responseDesc = viewErrorValidation($message);
 
            $codeRespone = 400;
        }
 
        $response = resultJson($responseError, $responseCode, $responseDesc, @$responseData);
        $this->response($response, $codeRespone);
    }
 
    function nasabah_detail_post()
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('ktp','ktp','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));
 
            $responseData  = $this->Model_ews->get_detail_nasabah($post['ktp'], $dateStart, $dateEnd);
 
            $responseError = false;
            $responseCode = "00";
            $responseDesc = "approve";
 
 
        }else{
            $message = $this->form_validation->error_array();
            $responseError = true;
            $responseCode = "01";
            $responseDesc = viewErrorValidation($message);
 
            $codeRespone = 400;
        }
 
        $response = resultJson($responseError, $responseCode, $responseDesc, @$responseData);
        $this->response($response, $codeRespone);
    }
}
 
/* End of file Ews.php */
/* Location: ./application/controllers/api/Ews.php */