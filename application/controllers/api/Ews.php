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
            $dataMapping = array();
            $temporaryMapping = array();
            $mappingNumber = 0;
            $mergeNumber = 0;
            $existDate = null;
            $newMapping = true;
            

            foreach($responseData as $key => $row) {
                
                if (count($responseData) != $key + 1) {
                    $date1 = strtotime($row->tanggal);
                    $date2 = strtotime($responseData[$key + 1]->tanggal);
                    $dateResult = $date2 - $date1;
                    $dateResult = round($dateResult / (60 * 60 * 24));
                    
                    if ($dateResult == 1) {
                        $temporaryMapping[] = $row;
                        $newMapping = false;
                    }
                    else {
                        // balikan perbandingan
                        if ($key != 0) {
                            $date2 = strtotime($row->tanggal);
                            $date1 = strtotime($responseData[$key-1]->tanggal);
                            $dateResult = $date2 - $date1;
                            $dateResult = round($dateResult / (60 * 60 * 24));
                            if ($dateResult == 1) {
                                $temporaryMapping[] = $row;
                                if (count($temporaryMapping) >= 3) {
                                    $newMapping = true;
                                }
                                else {
                                    $temporaryMapping = array();
                                    $mergeNumber = 0;        
                                }
                            }
                            else {
                                $temporaryMapping = array();
                                $mergeNumber = 0;    
                            }
                        }

                    }
                }
                else {
                    $date2 = strtotime($row->tanggal);
                    $date1 = strtotime($responseData[$key - 1]->tanggal);
                    $dateResult = $date2 - $date1;
                    $dateResult = round($dateResult / (60 * 60 * 24));
                    if ($dateResult == 1) {
                        $temporaryMapping[] = $row;
                        if (count($temporaryMapping) >= 3) {
                            $newMapping = true;
                        }
                    } else {
                        $temporaryMapping = array();
                        $mergeNumber = 0;
                    }
                }
                
                if ($newMapping == true && count($temporaryMapping) >= 3) {
                    $dataMapping[] = $temporaryMapping;
                    $temporaryMapping = array();
                    $mergeNumber = 0; 
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
        $dataResponse = array();
        foreach ($dataMapping as $key => $value) {
            $dataResponse[] = end($value);
        }
        
        $response = resultJson($responseError, $responseCode, $responseDesc, @$dataResponse);
        $this->response($response, $codeRespone);
    }
    
    function saldo_detail_post($type = 'kas')
    {
        $post = $this->post();
        $codeRespone = 200;
        
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules('idOutlet','idOutlet','required');
        $this->form_validation->set_rules('tanggal','tanggal','required');
        
        $responseData = null;
        
        if ($this->form_validation->run() == TRUE) {
            $dateStart = date("Y-m-d", strtotime($post['tanggal']));
            $dateEnd = date("Y-m-d", strtotime(" -3 day", strtotime($dateStart)));
            
            if ($type == 'kas') {
                $getLimit = $this->Model_ews->get_last_limit_kas($post['idOutlet']);
                $responseData  = $this->Model_ews->get_data_kas($post['idOutlet'], $dateStart, $dateEnd, $getLimit->limit_kas);
            }else if ($type == 'bank') {
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

    function taksiran_get()
    {
        $get = $this->get();
        $codeRespone = 200;

        $this->form_validation->set_data($get);
        $this->form_validation->set_rules('sample', 'sample','required');
        $this->form_validation->set_rules('branch', 'branch', 'required');
        $this->form_validation->set_rules('kat_kantong', 'kategori kantong', 'required');
        $this->form_validation->set_rules('kat_gudang', 'kategori gudang', 'required');


        if ($this->form_validation->run() == TRUE) {
            
            $responseData = array();
            
            $persen = $get['kat_kantong'] + $get['kat_gudang'];
            $jmlKantong = round($get['sample'] * $get['kat_kantong'] / 100);
            $jmlGudang = round($get['sample'] * $get['kat_gudang'] / 100);

            if ($persen != 100) {

                $responseError = true;
                $responseCode = "01";
                $responseDesc = 'Persen melebihi atau kurang dari 100 %';
                
                $codeRespone = 400;
                
            }
            else {

                $nikKantong = $this->Model_ews->get_nik_kantong($get['branch']);
                $nikGudang = $this->Model_ews->get_nik_gudang($get['branch']);

                $kantongData = array();
                $gudangData = array();

                // ambil data data persatu NIK

                foreach ($nikKantong as $key => $row) {
                    $dataKantong  = $this->Model_ews->get_random_kantong($get['branch'], $row->nikKtp);

                    if ($dataKantong != null) {
                        $kantongData[] = $dataKantong;
                    }
                }

                foreach ($nikGudang as $key => $rows) {
                    $dataGudang = $this->Model_ews->get_random_gudang($get['branch'], $rows->nikKtp);

                    if ($dataGudang != null) {
                        $gudangData[] = $dataGudang;
                    }
                }
                
                $mappingResult = array();
                $numberKantong = 0;
                $numberGudang = 0;

                foreach ($kantongData as $key => $row) {
                    if ($numberKantong < $jmlKantong) {
                        $responseData[] = $row;
                    }
                    $numberKantong++;
                }

                foreach ($gudangData as $key => $row) {
                    if ($numberGudang < $jmlGudang) {
                        $responseData[] = $row;
                    }
                    $numberGudang++;
                }

                // Update data yang tampil dengan memberi flag

                // foreach ($responseData as $key => $row) {
                //     $this->Model_ews->update_data_sample($row->id);
                // }
                
                $responseError = false;
                $responseCode = "00";
                $responseDesc = "approve";
            }

        } else {

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