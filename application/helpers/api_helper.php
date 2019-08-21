<?php

if ( ! function_exists('isCanAcces'))
{
    function isCanAcces() {
        return preg_match("/(android|passion)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}

if ( ! function_exists('resultJson'))
{
    function resultJson($responseError = true, $responseCode = "", $responseDesc = "", $data){
        // return array("responseError" => $responseError, "responseCode" => $responseCode ,"responseDesc" => $responseDesc, "data" => $data);
        if ($data) {
            $count = count($data);
        }else {
            $count = null;
        }
        return array("error" => $responseError,"code" => $responseCode,"message" => $responseDesc, "dataCount"=> @$count, "data" => @$data);
    }
}
if ( ! function_exists('resultLogin'))
{
    function resultLogin($responseError = true, $responseCode = "", $responseDesc = "", $data){
        // return array("responseError" => $responseError, "responseCode" => $responseCode ,"responseDesc" => $responseDesc, "data" => $data);
        return array("error" => $responseError,"message" => $responseDesc, "cookie" => $data);
    }
}

if ( ! function_exists('secretKey'))
{
    function secretKey(){

        $str = "4genp3G4da14n2oi8";
        return base64_decode($str);
    }
}

if (!function_exists('getModule')) {
    function getModule()
    {
        $CI = get_instance();
        $query = $CI->router->fetch_module();
        return $query;
    }
}

if (!function_exists('getController')) {
    function getController()
    {
        $CI = get_instance();
        $query = $CI->router->fetch_class();
        return $query;
    }
}

if (!function_exists('getFunction')) {
    function getFunction()
    {
        $CI = get_instance();
        $query = $CI->router->fetch_method();
        return $query;
    }
}

if ( ! function_exists('responseApi'))
{
    function responseApi($data)
    {
        $CI =& get_instance();
        $query = $CI->db->insert("temp_response",$data);

    }
}

if ( ! function_exists('getBulan'))
{ 
    function getBulan($bulan, $str = "") {
        $arr = array("1" => "Januari", "2" => "Februari", "3" => "Maret", "4" => "April", "5" => "Mei", "6" => "Juni", "7" => "Juli",
            "8" => "Agustus", "9" => "September", "01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli",
            "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
        $sub = array("1" => "Jan", "2" => "Feb", "3" => "Mar", "4" => "Apr", "5" => "May", "6" => "Jun", "7" => "Jul",
            "8" => "Aug", "9" => "Sep", "01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
        $hasil = $str ? @$sub["$bulan"] : @$arr["$bulan"];
        return $hasil;
    }
}

if ( ! function_exists('parseTanggal'))
{  
    function parseTanggal($tanggal){
        list($tahun,$bulan,$hari) = explode("-", $tanggal);
        return "$hari ".getBulan($bulan)." $tahun";
    }
}

if ( ! function_exists('get_filed_api'))
{
    function get_filed_api($field = '', $table = '', $where = '' , $whereData = '', $order_by = "")
    {
        $CI =& get_instance();
        // $query = $CI->db->insert("response_api",$data);

        $query = $CI->db->select($field);

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $query = $CI->db->where($key, $value);            
            }
        }else{
            $query = $CI->db->where($where, $whereData);            
        }

        if ($order_by != null) {
            $query = $CI->db->order_by($order_by, 'desc'); 
        }  

        $query = $CI->db->get($table);

        return $query->row();
    }
}

if ( ! function_exists('get_field_query'))
{

    function get_field_query($query)
    {
        $CI =& get_instance();
        // $query = $CI->db->insert("response_api",$data);

        $result = $CI->db->query($query);

        return $result->row();
    }
}

if ( ! function_exists('selisihMenit'))
{
    function selisihMenit($d1, $d2) {
        list($tanggalAwal, $waktuAwal) = explode(" ", $d1);
        list($tahunAwal, $bulanAwal, $hariAwal) = explode("-", $tanggalAwal);
        list($jamAwal, $menitAwal, $detikAwal) = explode(":", $waktuAwal);

        list($tanggalAkhir, $waktuAkhir) = explode(" ", $d2);
        list($tahunAkhir, $bulanAkhir, $hariAkhir) = explode("-", $tanggalAkhir);
        list($jamAkhir, $menitAkhir, $detikAkhir) = explode(":", $waktuAkhir);

        $dAwal = mktime($jamAwal, $menitAwal, $detikAwal, $bulanAwal, $hariAwal, $tahunAwal);
        $dAkhir = mktime($jamAkhir, $menitAkhir, $detikAkhir, $bulanAkhir, $hariAkhir, $tahunAkhir);
        return dateDiff("n", $dAwal, $dAkhir);
    }
}

if ( ! function_exists('selisihJam'))
{
    function selisihJam($d1,$d2) {
        list($tanggalAwal, $waktuAwal) = explode(" ", $d1);
        list($tahunAwal, $bulanAwal, $hariAwal) = explode("-", $tanggalAwal);
        list($jamAwal, $menitAwal, $detikAwal) = explode(":", $waktuAwal);

        list($tanggalAkhir, $waktuAkhir) = explode(" ", $d2);
        list($tahunAkhir, $bulanAkhir, $hariAkhir) = explode("-", $tanggalAkhir);
        list($jamAkhir, $menitAkhir, $detikAkhir) = explode(":", $waktuAkhir);            

        $dAwal=mktime($jamAwal, $menitAwal, $detikAwal,  $bulanAwal, $hariAwal, $tahunAwal);
        $dAkhir=mktime($jamAkhir, $menitAkhir, $detikAkhir,  $bulanAkhir, $hariAkhir, $tahunAkhir);
        return dateDiff("h", $dAwal, $dAkhir);  
    }
}


if ( ! function_exists('dateDiff'))
{
    function dateDiff($per, $d1, $d2) {
        $d = $d2 - $d1;
        switch ($per) {
            case "yyyy": $d/=12;
            case "m": $d*=12 * 7 / 365.25;
            case "ww": $d/=7;
            case "d": $d/=24;
            case "h": $d/=60;
            case "n": $d/=60;
        }
        return round($d) > 0 ? round($d) : round($d) * -1;
    }
}

if ( ! function_exists('randomNumber'))
{

    function randomNumber($digits = 0){
        $number = rand(pow(10, $digits-1), pow(10, $digits)-1);
        return $number;
    }
}

if ( ! function_exists('cekCode'))
{
    function cekCode()
    {

        $ci =& get_instance();
        return $ci->config;
    }
}

if ( ! function_exists('viewErrorValidation'))
{
    function viewErrorValidation($array)
    {
        $int = 0;
        $val = "";
        foreach ($array as $key => $value) {
            $int++;
            if ($int == 1) {
                $val = $value;
            }
        }

        return $val;
    }
}

if ( ! function_exists('generateRandomString'))
{
    function generateRandomString($length = 6, $tipe = "char")
    {
        if($tipe == "char") $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if($tipe == "num") $characters = "0123456789";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if ( ! function_exists('parseMoney'))
{

    function parseMoney($cash){
        if($cash>=1000000000000){ 
            $cash = round(($cash/1000000000000),2).' tr';

        }else if($cash>=1000000000){ 
            $cash = round(($cash/1000000000),2).' ml';

        }else if($cash>=1000000){ 
            $cash = round(($cash/1000000),2).' jt';

        }else if($cash>=1000){ 
            $cash = round(($cash/1000),2).' rb';

        }

        return $cash;
    }
}

?>