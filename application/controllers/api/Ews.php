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
            $result  = $this->Model_ews->get_data_nasabah($post['idOutlet'], $dateStart, $dateEnd);
 
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
        $this->form_validation->set_rules('nik','nik','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));
 
            $responseData  = $this->Model_ews->get_detail_nasabah($post['nik'], $dateStart, $dateEnd);
 
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

    public function karyawan_post() {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('idOutlet','idOutlet','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $responseData = array();
 
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));

            $responseData  = $this->Model_ews->get_data_karyawan($post['idOutlet'], $dateStart, $dateEnd);
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

    function karyawan_detail_post()
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('nip','nip','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));
 
            $responseData  = $this->Model_ews->get_detail_karyawan($post['nip'], $dateStart, $dateEnd);
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

    public function saldo_post($type = 'kas') {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('idOutlet','idOutlet','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $responseData = array();
 
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));

            if ($type == 'kas') {
                $getLimit = $this->Model_ews->get_last_limit_kas($post['idOutlet']);
                $responseData = $this->Model_ews->get_data_kas($post['idOutlet'], $dateStart, $dateEnd, $getLimit->limit_kas);

            }
            else if ($type == 'bank') {
                $getLimit = $this->Model_ews->get_last_limit_bank($post['idOutlet']);
                $responseData  = $this->Model_ews->get_data_bank($post['idOutlet'], $dateStart, $dateEnd, $getLimit->limit_bank);
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

    function saldo_detail_post($type = 'kas')
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('id','id','required');
        $this->form_validation->set_rules('idOutlet','idOutlet','required');

        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $dateStart = date("Y-m-d");
            $dateEnd = date("Y-m-d", strtotime("-2 week"));
            
            if ($type == 'kas') {
                $responseData  = $this->Model_ews->get_detail_kas($post['id'], $post['idOutlet'], $dateStart, $dateEnd);
            }else if ($type == 'bank') {
                $responseData  = $this->Model_ews->get_detail_bank($post['id'], $post['idOutlet'], $dateStart, $dateEnd);
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

    public function transaksi_post()
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
            $result  = $this->Model_ews->get_data_transaksi($post['idOutlet'], $dateStart, $dateEnd);
            
            foreach ($result as $key => $rows) {
                if ($rows->up > 500000000) {
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

    public function transaksi_detail_post()
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('id','id','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $responseData = array();
 
            $responseData  = $this->Model_ews->get_detail_transaksi($post['id']);
 
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

    public function lunas_post()
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

            $idOutlet = $this->Model_ews->get_outlet($post['idOutlet']);

            $responseData = $this->Model_ews->get_data_lunas($idOutlet, $dateStart, $dateEnd);
 
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

    public function lunas_detail_post()
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

            $idOutlet = $this->Model_ews->get_outlet($post['idOutlet']);

            $responseData = $this->Model_ews->get_detail_lunas($idOutlet, $dateStart, $dateEnd);
 
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

    public function bjdpl_post()
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
            $responseData = $this->Model_ews->get_data_bjdpl($post['idOutlet'], $dateStart, $dateEnd);
 
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

    public function bjdpl_detail_post()
    {
        $post = $this->post();
        $codeRespone = 200;
 
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('id','id','required');
 
        $responseData = null;
 
        if ($this->form_validation->run() == TRUE) {
            $responseData = array();
 
            $responseData  = $this->Model_ews->get_detail_bjdpl($post['id']);
 
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